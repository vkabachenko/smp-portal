<?php


namespace app\services\xml;

use app\models\BidComment;
use app\models\BidImage;
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
        if (is_null($bids)) {
            $bids =$this->getBidsAsArray();
        }

        $this->xmlArray = [
            [
                'tag' => 'ФайлОбмена',
                'attributes' => [
                    'ДатаВыгрузки' => date("dmYHis")
                ],
                'elements' => $bids
            ]
        ];
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
            'client_type' => $this->setXmlClientType($model->client_type),
            'client_name' => $model->client_name,
            'client_phone' => $model->client_phone,
            'client_address' => $model->client_address,
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
            'master_id' => $model->master_id ? $model->master->user->name : '',
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
        ];

        $changedAttributes = [];
        foreach ($this->workshop->bid_attributes_1c as $bidAttribute => $bidAttribute1C) {
            $changedAttributes[$bidAttribute1C] = $attributes[$bidAttribute];
        }

        $comments = $this->getCommentsAsArray($model->bidComments);
        $images = $this->getImagesAsArray($model->bidImages);
        $elements = array_merge($comments, $images);
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
        return $attribute === Bid::CLIENT_TYPE_LEGAL_ENTITY ? 'Юрлицо' : 'Физлицо';
    }
}