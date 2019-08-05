<?php

namespace app\services\xml;

use app\helpers\common\XmlHelper;
use app\models\Bid;
use app\models\BidComment;
use app\models\Brand;
use app\models\Condition;
use app\models\RepairStatus;
use app\models\User;
use app\models\WarrantyStatus;

class ReadService extends BaseService
{
    /* @var array */
    private $xmlArray = [];

    public function __construct($filename)
    {
        parent::__construct($filename);
        $this->createXmlArray();
    }

    public function createXmlArray()
    {
        $xml = XmlHelper::getXmlFromFile($this->filename);
        $this->xmlArray = XmlHelper::getArrayFromXml($xml);
    }

    public function setBids()
    {
        $bidsArray = $this->xmlArray['ДС'];
        foreach ($bidsArray as $bidArray) {
            $this->setBid($bidArray);
        }
    }

    private function setBid($bidArray)
    {
        $attributes = $bidArray['@attributes'];
        $bidComments = isset($bidArray['ТаблицаКомментариевСтрока']) ? $bidArray['ТаблицаКомментариевСтрока'] : [];

        $bid = $this->createBid($attributes);
        $this->createComments($bid, $bidComments);
    }

    private function createBid($attributes)
    {
        $model = new Bid();

        $brand = Brand::findByName($attributes['Бренд']);
        if (!$brand) {
            \Yii::error($attributes);
            return null;
        }

        $model->bid_1C_number = $attributes['Номер'];
        $model->client_name = $attributes['КлиентНаименование'];
        $model->client_phone = $attributes['КлиентТелефон1'];
        $model->created_at = $this->setDate($attributes['ДатаПринятияВРемонт']);
        $model->repair_status_id = RepairStatus::findIdByName($attributes['СтатусРемонта']);
        $model->equipment = $attributes['Оборудование'];
        $model->brand_id = $brand->id;
        $model->brand_name = $attributes['Бренд'];
        $model->manufacturer_id = $brand->manufacturer_id;
        $model->serial_number = $attributes['СерийныйНомер'];
        $model->condition_id = Condition::findIdByName($attributes['ВнешнийВид']);
        $model->composition_name = $attributes['Комплектность'];
        $model->defect = $attributes['Неисправность'];
        $model->treatment_type = $this->setTreatmentType($attributes['ТоварНаГарантии']);
        $model->purchase_date = $this->setDate($attributes['ДокументГарантииДата']);
        $model->vendor_code = $attributes['Артикул'];
        $model->bid_manufacturer_number = $attributes['НомерЗаявкиУПредставительства'];
        $model->warranty_status_id = WarrantyStatus::findIdByName($attributes['СтатусГарантии']);

        if (!$model->save()) {
            \Yii::error($attributes);
            \Yii::error($model->getErrors());
            return null;
        }

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
        $user = User::findByUsername($attributes['Автор']);
        $model = new BidComment();
        $model->bid_id = $bid->id;
        $model->user_id = $user ? $user->id : null;
        $model->created_at = $this->setDate($attributes['ДатаВремя']);
        $model->comment = $attributes['ТекстКомментария'];

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

}