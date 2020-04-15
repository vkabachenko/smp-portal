<?php


namespace app\models\form;


use yii\base\Model;

class WorkshopRulesForm extends Model
{
    public $paidBid;
    public $exchange1C;

    public function rules()
    {
        return [
            [['paidBid', 'exchange1C'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'paidBid' => 'Доступ к платным заявкам',
            'exchange1C' => 'Обмен с 1С'
        ];
    }

}