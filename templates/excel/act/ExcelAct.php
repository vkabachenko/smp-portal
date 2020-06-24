<?php
namespace app\templates\excel\act;

use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use app\templates\excel\ExcelActTemplate;
use app\helpers\common\DateHelper;

class ExcelAct extends ExcelActTemplate
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
        if (!($agency = $this->bid->getAgency())) {
            return;
        }
        if (!is_null($agency->act_template)) {
            PhpExcelTemplator::saveToFile($agency->getTemplatePath('act'), $this->getPath(), $this->getParams());
        }
    }

    protected function getParams()
    {
        return [
            '{bid_number}' => $this->bid->bid_number,
            '{created_at}' => DateHelper::getReadableDate($this->bid->created_at),
            '{equipment}' => $this->bid->equipment,
            '{brand_model_name}' => $this->bid->brand_model_name,
            '{serial_number}' => $this->bid->serial_number,
            '{purchase_date}' => DateHelper::getReadableDate($this->bid->purchase_date),
            '{client_name}' => $this->bid->client_id ? $this->bid->client->name : '',
            '{client_phone}' => $this->bid->client_id ? $this->bid->client->clientPhone : '',
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