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

   public function __construct($filename)
    {
        parent::__construct($filename);
        $this->createXmlArray();
    }

    public function createXmlfile()
    {
        $xmlConstructor = new XmlConstructor();
        $xml = $xmlConstructor->fromArray($this->xmlArray)->toOutput();
        file_put_contents($this->filename, $xml);
    }

    private function createXmlArray()
    {
        $bids = $this->getBidsAsArray();
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
            'GUID' => '',
            'БитриксID' => '',
            'Номер' => $model->bid_1C_number,
            'Дата' => '',
            'КлиентНаименование' => $model->client_name,
            'КлиентТелефон1' => $model->client_phone,
            'КлиентТелефон2' => '',
            'КлиентЭлПочта' => '',
            'ДатаПринятияВРемонт' => date("dmYHis", strtotime($model->created_at)),
            'СтатусРемонта' => $model->repair_status_id ? $model->repairStatus->name : '',
            'Оборудование' => $model->equipment,
            'Бренд' => $model->brand_name,
            'СерийныйНомер' => $model->serial_number,
            'ВнешнийВид' => $model->condition_id ? $model->condition->name : '',
            'Комплектность' => $model->composition_name,
            'Неисправность' => $model->defect,
            'ДополнительныеОтметки' => '',
            'ТоварНаГарантии' => $model->isWarranty() ? 'Истина' : 'Ложь',
            'ДокументГарантииНомер' => $model->warranty_number,
            'ДокументГарантииДата' => $model->purchase_date ? date("dmYHis", strtotime($model->purchase_date)) : '',
            'Мастер' => '',
            'Приемщик' => '',
            'РезультатДиагностики' => $model->diagnostic,
            'РекомендацииПоРемонту' => '',
            'Представительство' => $model->brand_name,
            'Артикул' => $model->vendor_code,
            'Продавец' => '',
            'РезультатДиагностикиДляПредставительства' => $model->diagnostic,
            'ЗаявленнаяНеисправностьДляПредставительства' => '',
            'НомерЗаявкиУПредставительства' => $model->bid_manufacturer_number,
            'СтатусГарантии' => $model->warranty_status_id ? $model->warrantyStatus->name : '',
            'ДатаПринятияВРемонтДляПредставительства' => '',
            'ДатаГотовности' => '',
            'ДефектГарантийный' => '',
            'ПроведениеРемонтаВозможно' => '',
            'ПоданоНаГарантию' => ''
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
            'Автор' => $bidComment->user->name,
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
            ->with(['bidComments', 'warrantyStatus', 'status', 'repairStatus', 'brand', 'condition'])
            ->all();
        return $models;
    }
}