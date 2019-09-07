<?php

namespace app\services\xml;

use app\helpers\common\XmlHelper;
use app\models\Bid;
use app\models\BidComment;
use app\models\BidHistory;
use app\models\Brand;
use app\models\Condition;
use app\models\RepairStatus;
use app\models\User;
use app\models\WarrantyStatus;

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
        $bidsArray = $this->xmlArray['ДС'];
        foreach ($bidsArray as $bidArray) {
            $this->setBid($bidArray);
        }
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
    }

    private function isDuplicate($attributes)
    {
        $bid1Cnumber = $attributes['Номер'];
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

        return $model;
    }

    private function fillAndValidateBid(Bid $model, $attributes)
    {
        $brand = Brand::findByName(trim($attributes['Бренд']));

        $model->guid = $attributes['GUID'];
        $model->bid_1C_number = $attributes['Номер'];
        $model->client_name = $attributes['КлиентНаименование'];
        $model->client_phone = $attributes['КлиентТелефон1'];
        $model->created_at = $this->setDate($attributes['ДатаПринятияВРемонт']);
        $model->repair_status_id = RepairStatus::findIdByName($attributes['СтатусРемонта']);
        $model->equipment = $attributes['Оборудование'];
        $model->brand_id = $brand ? $brand->id : null;
        $model->brand_name = trim($attributes['Бренд']);
        $model->manufacturer_id = $brand ? $brand->manufacturer_id : null;
        $model->serial_number = $attributes['СерийныйНомер'];
        $model->condition_id = Condition::findIdByName($attributes['ВнешнийВид']);
        $model->composition_name = $attributes['Комплектность'];
        $model->defect = $attributes['Неисправность'];
        $model->treatment_type = $this->setTreatmentType($attributes['ТоварНаГарантии']);
        $model->purchase_date = $this->setDate($attributes['ДокументГарантииДата']);
        $model->vendor_code = $attributes['Артикул'];
        $model->bid_manufacturer_number = $attributes['НомерЗаявкиУПредставительства'];
        $model->warranty_status_id = WarrantyStatus::findIdByName($attributes['СтатусГарантии']);
        $model->user_id = User::findIdByName($attributes['Мастер']);
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
        $model = Bid::find()->where(['bid_1C_number' => $attributes['Номер']])->one();

        if (!$this->fillAndValidateBid($model, $attributes)) {
            return null;
        }

        BidHistory::createUpdated($model, null, 'Изменена в 1C');

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
        $createdAt = $this->setDate($attributes['ДатаВремя']);
        $exists = BidComment::find()->where(['bid_id' => $bid->id, 'created_at' => $createdAt])->exists();

        if ($exists) {
            return;
        }

        $user = User::findByName($attributes['Автор']);
        $model = new BidComment();
        $model->bid_id = $bid->id;
        $model->user_id = $user ? $user->id : null;
        $model->created_at = $createdAt;
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