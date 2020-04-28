<?php

namespace app\models\form;


use yii\base\Model;

class ReportForm extends Model
{
    public $dateFrom;
    public $dateTo;
    public $reportDate;
    public $reportNom;

    public function rules()
    {
        return [
            [['dateFrom', 'dateTo'], 'required'],
            [['dateFrom', 'dateTo', 'reportDate', 'reportNom'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reportDate' => 'Дата отчета',
            'reportNom' => 'Номер отчета',
        ];
    }

}