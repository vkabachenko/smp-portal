<?php

namespace app\services\xml;

use app\helpers\common\XmlHelper;
use app\models\Bid;
use app\models\BidComment;
use app\models\BidHistory;
use app\models\Brand;
use app\models\BrandCorrespondence;
use app\models\Condition;
use app\models\Master;
use app\models\RepairStatus;
use app\models\User;
use app\models\WarrantyStatus;
use app\services\brand\BrandService;

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

    protected function setBid($bidArray)
    {
        $attributes = $bidArray['@attributes'];

        $bidComments = isset($bidArray['ТаблицаКомментариевСтрока']) ? $bidArray['ТаблицаКомментариевСтрока'] : [];

        if ($this->isDuplicate($attributes)) {
            $bid = $this->updateBid($attributes);
            $this->createComments($bid, $bidComments);
        } else {
            $bid = $this->createBid($attributes);
            $this->createComments($bid, $bidComments);
        }
        if (is_null($bid)) {
            $responseAttributes = [
                'ПорталID' => $this->getAttribute($attributes, 'ПорталID'),
                'GUID' => $this->getAttribute($attributes, 'GUID'),
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
        $bid1Cnumber = $this->getAttribute($attributes, 'Номер');
        $isExists = Bid::find()->where(['bid_1C_number' => $bid1Cnumber])->exists();

        return $isExists;
    }

    private function createBid($attributes)
    {
        $model = new Bid();

        if (!$this->fillAndValidateBid($model, $attributes)) {
            return null;
        }

        $model->save(false);

        $bidHistory = new BidHistory([
            'bid_id' => $model->id,
            'user_id' => null,
            'action' => 'Импортирована из 1С'
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
        $brandService = new BrandService(trim($this->getAttribute($attributes, 'Бренд')));

        $equipment = trim($this->getAttribute($attributes, 'Оборудование'));
        $equipment = $equipment ?: 'Оборудование не задано';

        $model->guid = $this->getAttribute($attributes, 'GUID');
        $model->bid_1C_number = $this->getAttribute($attributes, 'Номер');
        $model->client_name = $this->getAttribute($attributes, 'КлиентНаименование');
        $model->client_phone = $this->getAttribute($attributes, 'КлиентТелефон1');
        $model->created_at = $this->setDate($this->getAttribute($attributes, 'ДатаПринятияВРемонт'));
        $model->updated_at = $this->setDate($this->getAttribute($attributes, 'Дата'));
        $model->client_type = $this->setClientType($this->getAttribute($attributes, 'КлиентТип'));
        $model->repair_status_id = RepairStatus::findIdByName($this->getAttribute($attributes, 'СтатусРемонта'));
        $model->equipment = $equipment;
        $model->brand_id = $brandService->getBrandId();
        $model->brand_correspondence_id = $brandService->getBrandCorrespondenceId();
        $model->brand_name = $brandService->getName();
        $model->manufacturer_id = $brandService->getManufacturerId();
        $model->serial_number = $this->getAttribute($attributes, 'СерийныйНомер');
        $model->condition_id = Condition::findIdByName($this->getAttribute($attributes, 'ВнешнийВид'));
        $model->composition_name = $this->getAttribute($attributes, 'Комплектность');
        $model->defect = $this->getAttribute($attributes, 'Неисправность');
        $model->defect_manufacturer = $this->getAttribute($attributes, 'ЗаявленнаяНеисправностьДляПредставительства');
        $model->diagnostic = $this->getAttribute($attributes, 'РезультатДиагностики');
        $model->diagnostic_manufacturer = $this->getAttribute($attributes, 'РезультатДиагностикиДляПредставительства');
        $model->treatment_type = $this->setTreatmentType($this->getAttribute($attributes, 'ТоварНаГарантии'));
        $model->purchase_date = $this->setDate($this->getAttribute($attributes, 'ДокументГарантииДата'));
        $model->date_manufacturer = $this->setDate($this->getAttribute($attributes, 'ДатаПринятияВРемонтДляПредставительства'));
        $model->date_completion = $this->setDate($this->getAttribute($attributes, 'ДатаГотовности'));
        $model->vendor_code = $this->getAttribute($attributes, 'Артикул');
        $model->bid_manufacturer_number = $this->getAttribute($attributes, 'НомерЗаявкиУПредставительства');
        $model->warranty_status_id = WarrantyStatus::findIdByName($this->getAttribute($attributes, 'СтатусГарантии'));
        $model->master_id = Master::findIdByName($this->getAttribute($attributes, 'Мастер'));
        $model->user_id = User::findIdByName($this->getAttribute($attributes, 'Приемщик'));
        $model->saler_name = $this->getAttribute($attributes, 'Продавец');
        $model->repair_recommendations = $this->getAttribute($attributes, 'РекомендацииПоРемонту');
        $model->comment = $this->getAttribute($attributes, 'ДополнительныеОтметки');
        $model->is_warranty_defect = $this->setBoolean($this->getAttribute($attributes, 'ДефектГарантийный'));
        $model->is_repair_possible = $this->setBoolean($this->getAttribute($attributes, 'ПроведениеРемонтаВозможно'));
        $model->is_for_warranty = $this->setBoolean($this->getAttribute($attributes, 'ПоданоНаГарантию'));

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
        $model = Bid::find()->where(['bid_1C_number' => $this->getAttribute($attributes, 'Номер')])->one();

        if (!$this->fillAndValidateBid($model, $attributes)) {
            return null;
        }

        BidHistory::createUpdated($model->id, $model, null, 'Изменена в 1C');

        $model->save(false);

        return $model;
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
        $createdAt = $this->setDate($this->getAttribute($attributes, 'ДатаВремя'));
        $exists = BidComment::find()->where(['bid_id' => $bid->id, 'created_at' => $createdAt])->exists();

        if ($exists) {
            return;
        }

        $user = User::findByName($this->getAttribute($attributes, 'Автор'));
        $model = new BidComment();
        $model->bid_id = $bid->id;
        $model->user_id = $user ? $user->id : null;
        $model->created_at = $createdAt;
        $model->comment = $this->getAttribute($attributes, 'ТекстКомментария');

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
            return Bid::CLIENT_TYPE_PERSON;
        }

        if ($xmlClientType === 'Юрлицо') {
            return Bid::CLIENT_TYPE_LEGAL_ENTITY;
        }

        return Bid::CLIENT_TYPE_PERSON;
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

    private function getAttribute($attributes, $key)
    {
        return isset($attributes[$key]) ? $attributes[$key] : '';
    }

}