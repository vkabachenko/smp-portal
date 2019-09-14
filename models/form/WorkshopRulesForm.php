<?php


namespace app\models\form;


use yii\base\Model;

class WorkshopRulesForm extends Model
{
    public $paidBid;

    public function rules()
    {
        return [
            [['paidBid'], 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'paidBid' => 'Доступ к платным заявкам',
        ];
    }

}