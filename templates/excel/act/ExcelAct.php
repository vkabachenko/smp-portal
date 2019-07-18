<?php
namespace app\templates\excel\act;

use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use app\templates\excel\ExcelTemplate;

class ExcelAct extends ExcelTemplate
{

    public function getFilename()
    {
        return 'act_' . $this->bid->id . '.xlsx';
    }

    public function getDirectory()
    {
        return \Yii::getAlias('@app/templates/excel/act/generated/');
    }

    public function generate()
    {
        $manufacturer = $this->bid->manufacturer;
        if (!is_null($manufacturer->act_template)) {
            PhpExcelTemplator::saveToFile($manufacturer->getActTemplatePath(), $this->getPath(), $this->getParams());
        }
    }

    protected function getParams()
    {
        return [
            '{bid_number}' => $this->bid->bid_number,
            '{created_at}' => \Yii::$app->formatter->asDate($this->bid->created_at),
            '{equipment}' => $this->bid->equipment,
            '{brand_model_name}' => $this->bid->brand_model_name,
            '{serial_number}' => $this->bid->serial_number,
            '{purchase_date}' => \Yii::$app->formatter->asDate($this->bid->purchase_date),
            '{client_name}' => $this->bid->client_name,
            '{client_phone}' => $this->bid->client_phone,
            '{condition}' => $this->bid->condition_id ? $this->bid->condition->name : '',
            '{defect_warranty}' => $this->bid->isWarranty() ? $this->bid->defect : '',
            '{diagnostic_warranty}' => $this->bid->isWarranty() ? $this->bid->diagnostic : '',
            '{isWarranty}' => $this->bid->isWarranty() ? 'Да' : '',
            '{isPostPurchase}' => !$this->bid->isWarranty() ? 'Да' : '',
            '{defect}' => !$this->bid->isWarranty() ? $this->bid->defect : '',
            '{diagnostic}' => !$this->bid->isWarranty() ? $this->bid->diagnostic : ''
        ];
    }
}