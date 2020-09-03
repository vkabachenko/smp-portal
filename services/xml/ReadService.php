<?php

namespace app\services\xml;

use app\helpers\common\XmlHelper;
use app\models\Agency;
use app\models\Bid;
use app\models\BidComment;
use app\models\BidHistory;
use app\models\BidJob;
use app\models\BidJob1c;
use app\models\BidStatus;
use app\models\BrandCorrespondence;
use app\models\Client;
use app\models\ClientPhone;
use app\models\ClientProposition;
use app\models\JobsCatalog;
use app\models\ReplacementPart;
use app\models\Spare;
use app\models\User;
use app\services\brand\BrandService;
use Codeception\Module\Cli;

class ReadService extends BaseService
{
    /* @var array */
    protected $xmlArray = [];

    public function __construct($filename, $folder = 'xml')
    {
        parent::__construct($filename, $folder);
        $this->createXmlArray();
    }

    public function createXmlArray()
    {
        $xml = XmlHelper::getXmlFromFile($this->filename);
        $this->xmlArray = XmlHelper::getArrayFromXml($xml);
    }

    public function setClients()
    {
        $responseClients = [];
        $clientsArray = $this->xmlArray['Клиент'];
        if (isset($clientsArray['@attributes'])) {
            $responseClients[] = $this->setClient($clientsArray);
        } else {
            foreach ($clientsArray as $clientArray) {
                $responseClients[] = $this->setClient($clientArray);
            }
        }

        return $responseClients;
    }

    public function setBids()
    {
        $responseBids = [];
        $bidsArray = $this->xmlArray['ДС'];
        if (isset($bidsArray['@attributes'])) {
            $responseBids[] = $this->setBid($bidsArray);
        } else {
            foreach ($bidsArray as $bidArray) {
                $responseBids[] = $this->setBid($bidArray);
            }
        }

        return $responseBids;
    }

    protected function setClient($clientArray)
    {
        $attributes = $clientArray['@attributes'];

        if ($this->isClientDuplicate($attributes)) {
            $client = $this->updateClient($attributes);
        } else {
            $client = $this->createClient($attributes);
        }

        if (is_null($client)) {
            $responseAttributes = [
                'ПорталID' => $this->getCommentAttribute($attributes, 'ПорталID'),
                'GUID' => $this->getCommentAttribute($attributes, 'GUID'),
                'Успешно' => 'Ложь'
            ];
        } else {
            $responseAttributes = [
                'ПорталID' => $client->id,
                'GUID' => $client->guid,
                'Успешно' => 'Истина'
            ];
        }
        return [
            'tag' => 'Клиент',
            'attributes' => $responseAttributes
        ];
    }

    protected function setBid($bidArray)
    {
        $attributes = $bidArray['@attributes'];

        $bidComments = isset($bidArray['ТаблицаКомментариевСтрока']) ? $bidArray['ТаблицаКомментариевСтрока'] : [];
        $bidSpares = isset($bidArray['ЗапчастиДляПредставительстваСтрока']) ? $bidArray['ЗапчастиДляПредставительстваСтрока'] : [];
        $bidJobs = isset($bidArray['УслугиДляПредставительстваСтрока']) ? $bidArray['УслугиДляПредставительстваСтрока'] : [];
        $bidReplacementParts = isset($bidArray['АртикулыДляСервисаСтрока']) ? $bidArray['АртикулыДляСервисаСтрока'] : [];
        $bidClientPropositions = isset($bidArray['ПредложениеДляКлиентаСтрока']) ? $bidArray['ПредложениеДляКлиентаСтрока'] : [];

        if ($this->isDuplicate($attributes)) {
            $bid = $this->updateBid($attributes);
        } else {
            $bid = $this->createBid($attributes);
        }

        $this->createComments($bid, $bidComments);
        $this->createSpares($bid, $bidSpares);
        $this->createJobs($bid, $bidJobs);
        $this->createReplacementParts($bid, $bidReplacementParts);
        $this->createClientPropositions($bid, $bidClientPropositions);


        if (is_null($bid)) {
            $responseAttributes = [
                'ПорталID' => $this->get1Cattribute($attributes, 'id'),
                'GUID' => $this->get1Cattribute($attributes, 'guid'),
                'Успешно' => 'Ложь'
            ];
        } else {
            $responseAttributes = [
                'ПорталID' => $bid->id,
                'GUID' => $bid->guid,
                'Успешно' => 'Истина'
            ];
        }
        return [
            'tag' => 'ДС',
            'attributes' => $responseAttributes
        ];
    }

    private function isDuplicate($attributes)
    {
        $bid1Cnumber = $this->get1Cattribute($attributes, 'bid_1C_number');
        $isExists = is_null($bid1Cnumber) ? false : Bid::find()->where(['bid_1C_number' => $bid1Cnumber])->exists();

        return $isExists;
    }

    private function createBid($attributes)
    {
        $model = new Bid();

        if (!$this->fillAndValidateBid($model, $attributes)) {
            return null;
        }
        $model->status_id = $model->status_id ?: BidStatus::getId(BidStatus::STATUS_FILLED);

        $model->save(false);
        $model->bid_number = $model->id;
        $model->save(false);

        $bidHistory = new BidHistory([
            'bid_id' => $model->id,
            'user_id' => null,
            'action' => BidHistory::IMPORTED_1C
        ]);
        if (!$bidHistory->save()) {
            \Yii::error($bidHistory->getErrors());
        };

        if (is_null($model->brand_id) && is_null($model->brand_correspondence_id) && $model->isBrandName()) {
            $brandCorrespondence = new BrandCorrespondence([
                'name' => $model->brand_name,
                'brand_id' => null
            ]);
            if (!$brandCorrespondence->save()) {
                \Yii::error($brandCorrespondence->getErrors());
            };
        }

        return $model;
    }

    private function fillAndValidateBid(Bid $model, $attributes)
    {
        $brandService = new BrandService(trim($this->getAttribute($model, $attributes, 'brand_name', [$this, 'same'])));

        $equipment = trim($this->getAttribute($model, $attributes, 'equipment', [$this, 'same']));
        $equipment = $equipment ?: 'Оборудование не задано';

        $model->guid = $this->getAttribute($model, $attributes, 'guid', [$this, 'same']);
        $model->bid_1C_number = $this->getAttribute($model, $attributes, 'bid_1C_number', [$this, 'same']);
        $model->client_id = $this->getAttribute($model, $attributes, 'client_guid', [$this, 'setClientId']);
        $model->created_at = $this->getAttribute($model, $attributes, 'created_at', [$this, 'setDate']);
        $model->updated_at = $this->getAttribute($model, $attributes, 'updated_at', [$this, 'setDate']);
        $model->application_date = $this->getAttribute($model, $attributes, 'application_date', [$this, 'setDate']);
        $model->repair_status_id = $this->getAttribute($model, $attributes, 'repair_status_id',  '\app\models\RepairStatus::findIdByName');
        $model->equipment = $equipment;
        $model->brand_id = $brandService->getBrandId();
        $model->brand_correspondence_id = $brandService->getBrandCorrespondenceId();
        $model->brand_name = $brandService->getName();
        $model->manufacturer_id = $brandService->getManufacturerId();
        $model->brand_model_name = $this->getAttribute($model, $attributes, 'brand_model_name', [$this, 'same']);
        $model->serial_number = $this->getAttribute($model, $attributes, 'serial_number', [$this, 'same']);
        $model->condition_id = $this->getAttribute($model, $attributes, 'condition_id', '\app\models\Condition::findIdByName');
        $model->composition_name = $this->getAttribute($model, $attributes, 'composition_name', [$this, 'same']);
        $model->defect = $this->getAttribute($model, $attributes, 'defect', [$this, 'same']);
        $model->defect_manufacturer = $this->getAttribute($model, $attributes, 'defect_manufacturer', [$this, 'same']);
        $model->diagnostic = $this->getAttribute($model, $attributes, 'diagnostic', [$this, 'same']);
        $model->diagnostic_manufacturer = $this->getAttribute($model, $attributes, 'diagnostic_manufacturer', [$this, 'same']);
        $model->treatment_type = $this->getAttribute($model, $attributes, 'treatment_type', [$this, 'setTreatmentType']);
        $model->purchase_date = $this->getAttribute($model, $attributes, 'purchase_date', [$this, 'setDate']);
        $model->date_manufacturer = $this->getAttribute($model, $attributes, 'date_manufacturer', [$this, 'setDate']);
        $model->date_completion = $this->getAttribute($model, $attributes, 'date_completion', [$this, 'setDate']);
        $model->vendor_code = $this->getAttribute($model, $attributes, 'vendor_code', [$this, 'same']);
        $model->bid_manufacturer_number = $this->getAttribute($model, $attributes, 'bid_manufacturer_number', [$this, 'same']);
        $model->warranty_status_id = $this->getAttribute($model, $attributes, 'warranty_status_id', '\app\models\WarrantyStatus::findIdByName');
        $model->master_id = $this->getAttribute($model, $attributes, 'master_uuid', '\app\models\Master::findIdByUuid');
        $model->master_id = $model->master_id ?: $this->getAttribute($model, $attributes, 'master_name', '\app\models\Master::findIdByName');
        $model->user_id = $this->getAttribute($model, $attributes, 'user_id', '\app\models\User::findIdByName');
        $model->saler_name = $this->getAttribute($model, $attributes, 'saler_name', [$this, 'same']);
        $model->repair_recommendations = $this->getAttribute($model, $attributes, 'repair_recommendations', [$this, 'same']);
        $model->comment = $this->getAttribute($model, $attributes, 'comment', [$this, 'same']);
        $model->is_warranty_defect = $this->getAttribute($model, $attributes, 'is_warranty_defect', [$this, 'setBoolean']);
        $model->is_repair_possible = $this->getAttribute($model, $attributes, 'is_repair_possible', [$this, 'setBoolean']);
        $model->is_for_warranty = $this->getAttribute($model, $attributes, 'is_for_warranty', [$this, 'setBoolean']);
        $model->status_id = $this->getAttribute($model, $attributes, 'status_id', '\app\models\BidStatus::findIdByName');
        $model->decision_workshop_status_id = $this->getAttribute($model, $attributes, 'decision_workshop_status_id', '\app\models\DecisionWorkshopStatus::findIdByName');
        $model->decision_agency_status_id = $this->getAttribute($model, $attributes, 'decision_agency_status_id', '\app\models\DecisionAgencyStatus::findIdByName');
        $model->comment_1 = $this->getAttribute($model, $attributes, 'comment_1', [$this, 'same']);
        $model->comment_2 = $this->getAttribute($model, $attributes, 'comment_2', [$this, 'same']);
        $model->manager = $this->getAttribute($model, $attributes, 'manager', [$this, 'same']);
        $model->manager_contact = $this->getAttribute($model, $attributes, 'manager_contact', [$this, 'same']);
        $model->manager_presale = $this->getAttribute($model, $attributes, 'manager_presale', [$this, 'same']);
        $model->is_reappeal = $this->getAttribute($model, $attributes, 'is_reappeal', [$this, 'setBoolean']);
        $model->document_reappeal = $this->getAttribute($model, $attributes, 'document_reappeal', [$this, 'same']);
        $model->subdivision = $this->getAttribute($model, $attributes, 'subdivision', [$this, 'same']);
        $model->author = $this->getAttribute($model, $attributes, 'author', [$this, 'same']);
        $model->sum_manufacturer = $this->getAttribute($model, $attributes, 'sum_manufacturer', [$this, 'same']);
        $model->is_control = $this->getAttribute($model, $attributes, 'is_control', [$this, 'setBoolean']);
        $model->is_report = $this->getAttribute($model, $attributes, 'is_report', [$this, 'setBoolean']);
        $model->is_warranty = $this->getAttribute($model, $attributes, 'is_warranty', [$this, 'setBoolean']);
        $model->warranty_comment = $this->getAttribute($model, $attributes, 'warranty_comment', [$this, 'same']);
        $model->repair_status_date = $this->getAttribute($model, $attributes, 'repair_status_date', [$this, 'setDate']);
        $model->repair_status_author_id = $this->getAttribute($model, $attributes, 'repair_status_author_id', '\app\models\User::findIdByName');
        $model->agency_id = $model->agency_id ?: $this->getAttribute($model, $attributes, 'agency_id', [$this, 'setAgency']);

        $model->workshop_id = $this->workshop->id;
        $model->flag_export = true;

        if (!$model->validate()) {
            \Yii::error($attributes);
            \Yii::error($model->getErrors());
            return false;
        }
        return true;
    }

    private function updateBid($attributes)
    {
        /* @var $model Bid */
        $model = Bid::find()->where(['bid_1C_number' => $this->get1Cattribute($attributes, 'bid_1C_number')])->one();

        if (!$this->fillAndValidateBid($model, $attributes)) {
            return null;
        }

        BidHistory::createUpdated($model->id, $model, null, 'Изменена в 1C');

        $model->save(false);

        return $model;
    }

    public function isClientDuplicate($attributes)
    {
        $guid = $this->getCommentAttribute($attributes, 'GUID');
        $isExists = empty($guid) ? false : Client::find()->where(['guid' => $guid])->exists();

        return $isExists;
    }

    public function createClient($attributes)
    {
        $client = new Client();
        $client->guid = $this->getCommentAttribute($attributes, 'GUID');
        $client->name = $this->getCommentAttribute($attributes, 'Наименование');
        $client->full_name = $this->getCommentAttribute($attributes, 'НаименованиеПолное');
        $client->client_type = $this->setClientType($this->getCommentAttribute($attributes, 'КлиентТип'));
        $client->date_register = $this->setDate($this->getCommentAttribute($attributes, 'ДатаРегистрации'));
        $client->comment = $this->getCommentAttribute($attributes, 'Комментарий');
        $client->manager = $this->getCommentAttribute($attributes, 'ОсновнойМенеджер');
        $client->description = $this->getCommentAttribute($attributes, 'ДополнительнаяИнформация');
        $client->inn = $this->getCommentAttribute($attributes, 'ИНН');
        $client->kpp = $this->getCommentAttribute($attributes, 'КПП');
        $client->email = $this->getCommentAttribute($attributes, 'ЭлПочта');
        $client->address_legal = $this->getCommentAttribute($attributes, 'ЮридическийАдрес');
        $client->address_actual = $this->getCommentAttribute($attributes, 'ФактическийАдрес');
        $client->flag_export = true;
        $client->workshop_id = $this->workshop->id;

        if (!$client->save()) {
            \Yii::error($attributes);
            \Yii::error($client->getErrors());
        } else {
            $this->addClientPhone($client, $this->getCommentAttribute($attributes, 'Телефон1'));
            $this->addClientPhone($client, $this->getCommentAttribute($attributes, 'Телефон2'));
            $this->addClientPhone($client, $this->getCommentAttribute($attributes, 'Телефон3'));
        }

        return $client;
    }

    public function updateClient($attributes)
    {
        $client = Client::find()->where(['guid' => $this->getCommentAttribute($attributes, 'GUID')])->one();

        $client->name = $this->getCommentAttribute($attributes, 'Наименование');
        $client->full_name = $this->getCommentAttribute($attributes, 'НаименованиеПолное');
        $client->client_type = $this->setClientType($this->getCommentAttribute($attributes, 'КлиентТип'));
        $client->date_register = $this->setDate($this->getCommentAttribute($attributes, 'ДатаРегистрации'));
        $client->comment = $this->getCommentAttribute($attributes, 'Комментарий');
        $client->manager = $this->getCommentAttribute($attributes, 'ОсновнойМенеджер');
        $client->description = $this->getCommentAttribute($attributes, 'ДополнительнаяИнформация');
        $client->inn = $this->getCommentAttribute($attributes, 'ИНН');
        $client->kpp = $this->getCommentAttribute($attributes, 'КПП');
        $client->email = $this->getCommentAttribute($attributes, 'ЭлПочта');
        $client->address_legal = $this->getCommentAttribute($attributes, 'ЮридическийАдрес');
        $client->address_actual = $this->getCommentAttribute($attributes, 'ФактическийАдрес');
        $client->flag_export = true;
        $client->workshop_id = $this->workshop->id;

        if (!$client->save()) {
            \Yii::error($attributes);
            \Yii::error($client->getErrors());
        } else {
            ClientPhone::deleteAll(['client_id' => $client->id]);

            $this->addClientPhone($client, $this->getCommentAttribute($attributes, 'Телефон1'));
            $this->addClientPhone($client, $this->getCommentAttribute($attributes, 'Телефон2'));
            $this->addClientPhone($client, $this->getCommentAttribute($attributes, 'Телефон3'));
        }

        return $client;
    }

    private function addClientPhone(Client $client, $phone)
    {
        $clientPhone = new ClientPhone(['client_id' => $client->id, 'phone' => $phone]);
        if (!$clientPhone->save()) {
            \Yii::error($clientPhone->getErrors());
        }
    }

    private function createComments($bid, $bidComments)
    {
        if (!$bid) {
            return;
        }

        if (isset($bidComments['@attributes'])) {
            $this->createComment($bid, $bidComments['@attributes']);
        } else {
            foreach ($bidComments as $bidComment) {
                $this->createComment($bid, $bidComment['@attributes']);
            }
        }
    }

    private function createComment($bid, $attributes)
    {
        $createdAt = $this->setDate($this->getCommentAttribute($attributes, 'ДатаВремя'));
        $exists = BidComment::find()->where(['bid_id' => $bid->id, 'created_at' => $createdAt])->exists();

        if ($exists) {
            return;
        }

        $user = User::findByName($this->getCommentAttribute($attributes, 'Автор'));
        $model = new BidComment();
        $model->bid_id = $bid->id;
        $model->user_id = $user ? $user->id : null;
        $model->created_at = $createdAt;
        $model->comment = $this->getCommentAttribute($attributes, 'ТекстКомментария');

        if (!$model->save()) {
            \Yii::error($attributes);
            \Yii::error($model->getErrors());
        }
    }

    private function createSpares($bid, $bidSpares)
    {
        if (!$bid || empty($bidSpares)) {
            return;
        }

        Spare::deleteAll(['bid_id' => $bid->id]);

        if (isset($bidSpares['@attributes'])) {
            $this->createSpare($bid, $bidSpares['@attributes']);
        } else {
            foreach ($bidSpares as $bidSpare) {
                $this->createSpare($bid, $bidSpare['@attributes']);
            }
        }
    }

    private function createSpare($bid, $attributes)
    {
        $model = new Spare();
        $model->bid_id = $bid->id;
        $model->name = $this->getCommentAttribute($attributes, 'Наименование');
        $model->quantity = $this->getCommentAttribute($attributes, 'Количество');
        $model->vendor_code = $this->getCommentAttribute($attributes, 'Артикул');
        $model->price = $this->getCommentAttribute($attributes, 'Цена');
        $model->total_price = $this->getCommentAttribute($attributes, 'Сумма');
        $model->num_order = intval($this->getCommentAttribute($attributes, 'НомерСтроки'));

        if (!$model->save()) {
            \Yii::error($attributes);
            \Yii::error($model->getErrors());
        }
    }

    private function createJobs($bid, $bidJobs)
    {
        if (!$bid || empty($bidJobs)) {
            return;
        }

        BidJob1c::deleteAll(['bid_id' => $bid->id]);

        if (isset($bidJobs['@attributes'])) {
            $this->createJob($bid, $bidJobs['@attributes']);
        } else {
            foreach ($bidJobs as $bidJob) {
                $this->createJob($bid, $bidJob['@attributes']);
            }
        }
    }

    private function createJob($bid, $attributes)
    {
        $model = new BidJob1c();
        $model->bid_id = $bid->id;
        $model->name = $this->getCommentAttribute($attributes, 'Наименование');
        $model->quantity = $this->getCommentAttribute($attributes, 'Количество');
        $model->price = $this->getCommentAttribute($attributes, 'Цена');
        $model->total_price = $this->getCommentAttribute($attributes, 'Сумма');
        $model->num_order = intval($this->getCommentAttribute($attributes, 'НомерСтроки'));

        if (!$model->save()) {
            \Yii::error($attributes);
            \Yii::error($model->getErrors());
        }
    }

    private function createReplacementParts($bid, $bidReplacementParts)
    {
        if (!$bid || empty($bidReplacementParts)) {
            return;
        }

        ReplacementPart::deleteAll(['bid_id' => $bid->id]);

        if (isset($bidReplacementParts['@attributes'])) {
            $this->createReplacementPart($bid, $bidReplacementParts['@attributes']);
        } else {
            foreach ($bidReplacementParts as $bidReplacementPart) {
                $this->createReplacementPart($bid, $bidReplacementPart['@attributes']);
            }
        }
    }

    private function createReplacementPart($bid, $attributes)
    {
        $model = new ReplacementPart();
        $model->bid_id = $bid->id;
        $model->vendor_code = $this->getCommentAttribute($attributes, 'Артикул');
        $model->vendor_code_replacement = $this->getCommentAttribute($attributes, 'АртикулЗамена');
        $model->is_agree = $this->setBoolean($this->getCommentAttribute($attributes, 'ПризнакСогласия'));
        $model->name = $this->getCommentAttribute($attributes, 'Наименование');
        $model->price = $this->getCommentAttribute($attributes, 'Цена');
        $model->quantity = $this->getCommentAttribute($attributes, 'Количество');
        $model->total_price = $this->getCommentAttribute($attributes, 'Сумма');
        $model->manufacturer = $this->getCommentAttribute($attributes, 'Производитель');
        $model->link1C = $this->getCommentAttribute($attributes, 'СсылкаВ1С');
        $model->comment = $this->getCommentAttribute($attributes, 'Комментарий');
        $model->status = $this->getCommentAttribute($attributes, 'Статус');
        $model->is_to_buy = $this->setBoolean($this->getCommentAttribute($attributes, 'Купить'));
        $model->num_order = intval($this->getCommentAttribute($attributes, 'НомерСтроки'));

        if (!$model->save()) {
            \Yii::error($attributes);
            \Yii::error($model->getErrors());
        }
    }

    private function createClientPropositions($bid, $bidClientPropositions)
    {
        if (!$bid || empty($bidClientPropositions)) {
            return;
        }

        ClientProposition::deleteAll(['bid_id' => $bid->id]);

        if (isset($bidClientPropositions['@attributes'])) {
            $this->createClientProposition($bid, $bidClientPropositions['@attributes']);
        } else {
            foreach ($bidClientPropositions as $bidClientProposition) {
                $this->createClientProposition($bid, $bidClientProposition['@attributes']);
            }
        }
    }

    private function createClientProposition($bid, $attributes)
    {
        $model = new ClientProposition();
        $model->bid_id = $bid->id;
        $model->name = $this->getCommentAttribute($attributes, 'Наименование');
        $model->price = $this->getCommentAttribute($attributes, 'Цена');
        $model->quantity = $this->getCommentAttribute($attributes, 'Количество');
        $model->total_price = $this->getCommentAttribute($attributes, 'Сумма');
        $model->num_order = intval($this->getCommentAttribute($attributes, 'НомерСтроки'));

        if (!$model->save()) {
            \Yii::error($attributes);
            \Yii::error($model->getErrors());
        }
    }

    private function setDate($xmlDate)
    {
        if (!empty($xmlDate)) {
            return \DateTime::createFromFormat('dmYHis', $xmlDate)->format('Y-m-d H:i:s');
        } else {
            return null;
        }
    }

    private function setTreatmentType($xmlTreatmentType)
    {
        if ($xmlTreatmentType === 'Истина') {
            return Bid::TREATMENT_TYPE_WARRANTY;
        }

        if ($xmlTreatmentType === 'Ложь') {
            return Bid::TREATMENT_TYPE_PRESALE;
        }

        return null;
    }

    private function setClientType($xmlClientType)
    {
        if ($xmlClientType === 'Физлицо') {
            return Client::CLIENT_TYPE_PERSON;
        }

        if ($xmlClientType === 'Юрлицо') {
            return Client::CLIENT_TYPE_LEGAL_ENTITY;
        }

        return Client::CLIENT_TYPE_PERSON;
    }

    private function setBoolean($xmlBoolean, $default = false)
    {
        if ($xmlBoolean === 'Ложь') {
            return false;
        }

        if ($xmlBoolean === 'Истина') {
            return true;
        }

        return $default;
    }

    private function setAgency($name)
    {
        if (is_null($name) || is_numeric($name)) {
            return $name;
        }

        $agency = Agency::find()
            ->joinWith('manufacturer', false)
            ->where(['manufacturer.name' => $name])
            ->one();

        return $agency ? $agency->id : null;
    }

    private function setClientId($guid)
    {
        if (empty($guid)) {
            return null;
        }
        $client = Client::find()->where(['guid' => $guid])->one();
        if (is_null($client)) {
            return null;
        } else {
            return $client->id;
        }
    }

    private function getAttribute(Bid $bid, $attributes, $key, callable $f)
    {
        $key1C = isset($this->workshop->bid_attributes_1c[$key]) ? $this->workshop->bid_attributes_1c[$key] : null;

        if (is_null($key1C)) {
            return  isset($bid->$key) ? $bid->$key : null;
        } else {
            return isset($attributes[$key1C]) ? $f($attributes[$key1C]) : (isset($bid->$key) ? $bid->$key : null);
        }
    }

    protected function get1Cattribute($attributes, $key)
    {
        $key1C = isset($this->workshop->bid_attributes_1c[$key]) ? $this->workshop->bid_attributes_1c[$key] : null;
        if (is_null($key1C)) {
            return  null;
        } else {
            return isset($attributes[$key1C]) ? $attributes[$key1C] : null;
        }
    }

    protected function getCommentAttribute($attributes, $key)
    {
        return isset($attributes[$key]) ? $attributes[$key] : '';
    }

    private function same($a)
    {
        return $a;
    }

}