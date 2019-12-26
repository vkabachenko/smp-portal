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

   public function __construct($filename, $bids = null)
    {
        parent::__construct($filename);
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
            'ПорталID' => $model->id,
            'GUID' => $model->guid,
            'Номер' => $model->bid_1C_number,
            'Дата' => $this->setXmlDate($model->updated_at),
            'КлиентТип' => $this->setXmlClientType($model->client_type),
            'КлиентНаименование' => $model->client_name,
            'КлиентТелефон1' => $model->client_phone,
            //'КлиентТелефон2' => '',
            //'КлиентЭлПочта' => '',
            'ДатаПринятияВРемонт' => $this->setXmlDate($model->created_at),
            'СтатусРемонта' => $model->repair_status_id ? $model->repairStatus->name : '',
            'Оборудование' => $model->equipment,
            'Бренд' => $model->brand_correspondence_id ? $model->brandCorrespondence->name : $model->brand_name,
            'СерийныйНомер' => $model->serial_number,
            'ВнешнийВид' => $model->condition_id ? $model->condition->name : '',
            'Комплектность' => $model->composition_name,
            'Неисправность' => $model->defect,
            'ДополнительныеОтметки' => $model->comment,
            'ТоварНаГарантии' => $this->setXmlBoolean($model->isWarranty()),
            'ДокументГарантииНомер' => $model->warranty_number,
            'ДокументГарантииДата' => $this->setXmlDate($model->purchase_date),
            'Мастер' => $model->master_id ? $model->master->user->name : '',
            'Приемщик' => $model->user_id ? $model->user->name : '',
            'РезультатДиагностики' => $model->diagnostic,
            'РекомендацииПоРемонту' => $model->repair_recommendations,
            'Представительство' => $model->manufacturer_id ? $model->manufacturer->name : '',
            'Артикул' => $model->vendor_code,
            'Продавец' => $model->saler_name,
            'РезультатДиагностикиДляПредставительства' => $model->diagnostic_manufacturer,
            'ЗаявленнаяНеисправностьДляПредставительства' => $model->defect_manufacturer,
            'НомерЗаявкиУПредставительства' => $model->bid_manufacturer_number,
            'СтатусГарантии' => $model->warranty_status_id ? $model->warrantyStatus->name : '',
            'ДатаПринятияВРемонтДляПредставительства' => $this->setXmlDate($model->date_manufacturer),
            'ДатаГотовности' => $this->setXmlDate($model->date_completion),
            'ДефектГарантийный' => $this->setXmlBoolean($model->is_warranty_defect),
            'ПроведениеРемонтаВозможно' => $this->setXmlBoolean($model->is_repair_possible),
            'ПоданоНаГарантию' => $this->setXmlBoolean($model->is_for_warranty)
        ];
        $comments = $this->getCommentsAsArray($model->bidComments);
        $images = $this->getImagesAsArray($model->bidImages);
        $elements = array_merge($comments, $images);
        $bid = [
            'tag' => 'ДС',
            'attributes' => $attributes
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