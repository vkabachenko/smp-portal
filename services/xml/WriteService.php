<?php


namespace app\services\xml;

use app\models\BidComment;
use app\models\BidImage;
use app\models\BidJob;
use app\models\BidJob1c;
use app\models\Client;
use app\models\ClientProposition;
use app\models\ReplacementPart;
use app\models\Spare;
use app\models\User;
use bupy7\xml\constructor\XmlConstructor;
use app\models\Bid;
use function GuzzleHttp\Psr7\str;
use yii\helpers\Html;

class WriteService extends BaseService
{
    /* @var array */
    private $xmlArray = [];

   public function __construct($filename, $workshop, $bids = null)
    {
        parent::__construct($filename, $workshop);
        $this->createXmlArray($bids);
    }

    public function createXmlfile()
    {
        $xmlConstructor = new XmlConstructor();
        $xml = $xmlConstructor->fromArray($this->xmlArray)->toOutput();
        file_put_contents($this->filename, $xml);
    }

    private function createXmlArray($bids)
    {
        $clients = $this->getClientsAsArray();

        if (is_null($bids)) {
            $bids =$this->getBidsAsArray();
        }

        $this->xmlArray = [
            [
                'tag' => 'ФайлОбмена',
                'attributes' => [
                    'ДатаВыгрузки' => date("dmYHis")
                ],
                'elements' => array_merge($clients, $bids)
            ]
        ];
    }

    private function getClientsAsArray()
    {
        $bids = [];
        $models = $this->getRecentClients();
        foreach ($models as $model) {
            $bids[] = $this->getClientAsArray($model);
        }
        return $bids;
    }

    private function getClientAsArray(Client $client)
    {
        $phones = $client->clientPhones;

        $attributes = [
            'GUID' => $client->guid,
            'ПорталID' => $client->id,
            'Наименование' => $client->name,
            'КлиентТип' => $this->setXmlClientType($client->client_type),
            'ДатаРегистрации' => $this->setXmlDate($client->date_register),
            'Комментарий' => $client->comment,
            'НаименованиеПолное' => $client->full_name,
            'ОсновнойМенеджер' => $client->manager,
            'ДополнительнаяИнформация' => $client->description,
            'ИНН' => $client->inn,
            'КПП' => $client->kpp,
            'ЭлПочта' => $client->email,
            'ЮридическийАдрес' => $client->address_legal,
            'ФактическийАдрес' => $client->address_actual,
            'Телефон1' => isset($phones[0]) ? $phones[0]->phone : '',
            'Телефон2' => isset($phones[1]) ? $phones[1]->phone : '',
            'Телефон3' => isset($phones[2]) ? $phones[2]->phone : '',
        ];
        $clientArray = [
            'tag' => 'Клиент',
            'attributes' => $attributes
        ];
        return $clientArray;
    }

    private function getBidsAsArray()
    {
        $bids = [];
        $models = $this->getRecentBids();
        foreach ($models as $model) {
            $bids[] = $this->getBidAsArray($model);
        }
        return $bids;
    }

    private function getBidAsArray(Bid $model)
    {
        $attributes = [
            'id' => $model->id,
            'guid' => $model->guid,
            'bid_1C_number' => $model->bid_1C_number,
            'updated_at' => $this->setXmlDate($model->updated_at),
            'client_id' => $model->client_id,
            'client_guid' => $model->client_id ? $model->client->guid : '',
            'created_at' => $this->setXmlDate($model->created_at),
            'status_id' => $model->status_id ? $model->status->name : '',
            'repair_status_id' => $model->repair_status_id ? $model->repairStatus->name : '',
            'equipment' => $model->equipment,
            'brand_name' => $model->brand_correspondence_id ? $model->brandCorrespondence->name : $model->brand_name,
            'brand_model_name' => $model->brand_model_name,
            'serial_number'  => $model->serial_number,
            'condition_id' => $model->condition_id ? $model->condition->name : '',
            'composition_name' => $model->composition_name,
            'defect' => $model->defect,
            'comment' => $model->comment,
            'treatment_type' => $this->setXmlBoolean($model->isWarranty()),
            'warranty_number' => $model->warranty_number,
            'purchase_date'  => $this->setXmlDate($model->purchase_date),
            'application_date' => $this->setXmlDate($model->application_date),
            'master_name' => $model->master_id ? $model->master->user->name : '',
            'master_uuid' => $model->master_id ? $model->master->uuid : '',
            'user_id' => $model->user_id ? $model->user->name : '',
            'diagnostic' => $model->diagnostic,
            'repair_recommendations'  => $model->repair_recommendations,
            'manufacturer_id' => $model->manufacturer_id ? $model->manufacturer->name : '',
            'vendor_code' => $model->vendor_code,
            'saler_name' => $model->saler_name,
            'diagnostic_manufacturer'  => $model->diagnostic_manufacturer,
            'defect_manufacturer'  => $model->defect_manufacturer,
            'bid_manufacturer_number' => $model->bid_manufacturer_number,
            'warranty_status_id' => $model->warranty_status_id ? $model->warrantyStatus->name : '',
            'date_manufacturer' => $this->setXmlDate($model->date_manufacturer),
            'date_completion' => $this->setXmlDate($model->date_completion),
            'is_warranty_defect' => $this->setXmlBoolean($model->is_warranty_defect),
            'is_repair_possible' => $this->setXmlBoolean($model->is_repair_possible),
            'is_for_warranty' => $this->setXmlBoolean($model->is_for_warranty),
            'decision_workshop_status_id' => $model->decision_workshop_status_id ? $model->decisionWorkshopStatus->name : '',
            'decision_agency_status_id' => $model->decision_agency_status_id ? $model->decisionAgencyStatus->name : '',
            'comment_1' => $model->comment_1,
            'comment_2' => $model->comment_2,
            'manager' => $model->manager,
            'manager_contact' => $model->manager_contact,
            'manager_presale' => $model->manager_presale,
            'is_reappeal' => $this->setXmlBoolean($model->is_reappeal),
            'document_reappeal' => $model->document_reappeal,
            'subdivision' => $model->subdivision,
            'repair_status_date' => $this->setXmlDate($model->repair_status_date),
            'repair_status_author_id' => $model->repair_status_author_id ? User::findOne($model->repair_status_author_id)->name : null,
            'author' => $model->author,
            'sum_manufacturer' => $model->sum_manufacturer,
            'is_control' => $this->setXmlBoolean($model->is_control),
            'is_report' => $this->setXmlBoolean($model->is_report),
            'is_warranty' => $this->setXmlBoolean($model->is_warranty),
            'warranty_comment' => $model->warranty_comment,
            'agency_id' => $model->agency_id ? $model->getAgency()->manufacturer->name : ''
        ];

        $changedAttributes = [];
        foreach ($this->workshop->bid_attributes_1c as $bidAttribute => $bidAttribute1C) {
            $changedAttributes[$bidAttribute1C] = $attributes[$bidAttribute];
        }

        $comments = $this->getCommentsAsArray($model->bidPrivateComments);
        $spares = $this->getSparesAsArray($model->spares);
        $jobs = $this->getJobsAsArray($model->jobs1c);
        $replacementParts = $this->getReplacementPartsAsArray($model->replacementParts);
        $clientPropositions = $this->getClientPropositionsAsArray($model->clientPropositions);
        $images = $this->getImagesAsArray($model->bidImages);
        $elements = array_merge($comments, $spares, $jobs, $replacementParts, $clientPropositions, $images);
        $bid = [
            'tag' => 'ДС',
            'attributes' => $changedAttributes
        ];
        if (count($elements) > 0) {
            $bid['elements'] = $elements;
        }
        return $bid;
    }


    private function getCommentsAsArray($bidComments)
    {
        $comments = [];
        foreach ($bidComments as $index => $bidComment) {
            $comments[] = $this->getCommentAsArray($index, $bidComment);
        }
        return $comments;
    }

    private function getCommentAsArray($index, BidComment $bidComment)
    {
        $attributes = [
            'НомерСтроки' => $index + 1,
            'Автор' => $bidComment->user_id ? $bidComment->user->name : '',
            'ДатаВремя' => date("dmYHis", strtotime($bidComment->created_at)),
            'ТекстКомментария' => Html::encode($bidComment->comment)
        ];
        $comment = [
            'tag' => 'ТаблицаКомментариевСтрока',
            'attributes' => $attributes
        ];
        return $comment;
    }

    private function getSparesAsArray($spares)
    {
        $elements = [];
        foreach ($spares as $spare) {
            $elements[] = $this->getSpareAsArray($spare);
        }
        return $elements;
    }

    private function getSpareAsArray(Spare $spare)
    {
        $attributes = [
            'Артикул' => $spare->vendor_code,
            'Наименование' => $spare->name,
            'Количество' => $spare->quantity,
            'Цена' => $spare->price,
            'Сумма' => $spare->total_price,
            'НомерСтроки' => $spare->num_order,
        ];
        $element = [
            'tag' => 'ЗапчастиДляПредставительстваСтрока',
            'attributes' => $attributes
        ];
        return $element;
    }

    private function getJobsAsArray($jobs)
    {
        $elements = [];
        foreach ($jobs as $job) {
            $elements[] = $this->getJobAsArray($job);
        }
        return $elements;
    }

    private function getJobAsArray(BidJob1c $job)
    {
        $attributes = [
            'Наименование' => $job->name,
            'Количество' => $job->quantity,
            'Цена' => $job->price,
            'Сумма' => $job->total_price,
            'НомерСтроки' => $job->num_order,
        ];
        $element = [
            'tag' => 'УслугиДляПредставительстваСтрока',
            'attributes' => $attributes
        ];
        return $element;
    }

    private function getReplacementPartsAsArray($replacementParts)
    {
        $elements = [];
        foreach ($replacementParts as $replacementPart) {
            $elements[] = $this->getReplacementPartAsArray($replacementPart);
        }
        return $elements;
    }

    private function getReplacementPartAsArray(ReplacementPart $replacementPart)
    {
        $attributes = [
            'Артикул' => $replacementPart->vendor_code,
            'АртикулЗамена' => $replacementPart->vendor_code_replacement,
            'ПризнакСогласия' => $this->setXmlBoolean($replacementPart->is_agree),
            'Наименование' => $replacementPart->name,
            'Цена' => $replacementPart->price,
            'Количество' => $replacementPart->quantity,
            'Сумма' => $replacementPart->total_price,
            'Производитель' => $replacementPart->manufacturer,
            'СсылкаВ1С' => $replacementPart->link1C,
            'Комментарий' => $replacementPart->comment,
            'Статус' => $replacementPart->status,
            'Купить' => $this->setXmlBoolean($replacementPart->is_to_buy),
            'НомерСтроки' => $replacementPart->num_order,
        ];
        $element = [
            'tag' => 'АртикулыДляСервисаСтрока',
            'attributes' => $attributes
        ];
        return $element;
    }

    private function getClientPropositionsAsArray($clientPropositions)
    {
        $elements = [];
        foreach ($clientPropositions as $clientProposition) {
            $elements[] = $this->getClientPropositionAsArray($clientProposition);
        }
        return $elements;
    }

    private function getClientPropositionAsArray(ClientProposition $clientProposition)
    {
        $attributes = [
            'Наименование' => $clientProposition->name,
            'Цена' => $clientProposition->price,
            'Количество' => $clientProposition->quantity,
            'Сумма' => $clientProposition->total_price,
            'НомерСтроки' => $clientProposition->num_order,
        ];
        $element = [
            'tag' => 'ПредложениеДляКлиентаСтрока',
            'attributes' => $attributes
        ];
        return $element;
    }

    private function getImagesAsArray($bidImages)
    {
        $images = [];
        foreach ($bidImages as $index => $bidImage) {
            $images[] = $this->getImageAsArray($index, $bidImage);
        }
        return $images;
    }

    private function getImageAsArray($index, BidImage $bidImage)
    {
        $attributes = [
            'НомерСтроки' => $index + 1,
            'Ссылка' => $bidImage->getAbsoluteUrl(),
        ];
        $image = [
            'tag' => 'ФотоСтрока',
            'attributes' => $attributes
        ];
        return $image;
    }



    private function getRecentClients()
    {
        $models = Client::find()
            ->where(['flag_export' => false])
            ->andWhere(['workshop_id' => $this->workshop->id])
            ->all();
        return $models;
    }

    private function getRecentBids()
    {
        $models = Bid::find()
            ->with(['bidComments', 'warrantyStatus', 'status', 'repairStatus', 'brand', 'condition', 'bidImages'])
            ->where(['flag_export' => false])
            ->andWhere(['workshop_id' => $this->workshop->id])
            ->all();
        return $models;
    }

    private function setXmlDate($attribute)
    {
        return $attribute ? date("dmYHis", strtotime($attribute)) : '';
    }

    private function setXmlBoolean($attribute)
    {
        return $attribute ? 'Истина' : 'Ложь';
    }

    private function setXmlClientType($attribute)
    {
        return $attribute === Client::CLIENT_TYPE_LEGAL_ENTITY ? 'Юрлицо' : 'Физлицо';
    }
}