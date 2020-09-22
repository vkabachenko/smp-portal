<?php
namespace app\templates\excel\act;

use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use app\models\BidJob;
use app\models\Spare;
use app\models\TemplateModel;
use app\templates\excel\ExcelActTemplate;
use app\helpers\common\DateHelper;

class ExcelAct extends ExcelActTemplate
{
    public function getFilename()
    {
        return $this->decision && $this->decision->sub_type_act
            ? $this->decision->sub_type_act . '-' . $this->bid->id . '.xlsx'
            : '';
    }

    public function getDirectory()
    {
        return \Yii::getAlias('@app/templates/excel/act/generated/');
    }

    public function generate()
    {
        if (empty($this->template)) {
            return;
        }

        PhpExcelTemplator::saveToFile($this->template->getTemplatePath(), $this->getPath(), $this->getParams());
    }

    protected function getParams()
    {
        $jobs = $this->bid->jobs;
        $spares = $this->bid->spares;

        return [
            '{contract_nom}' => $this->bid->getAgencyWorkshop() ? $this->bid->getAgencyWorkshop()->contract_nom : '',
            '{contract_date}' => $this->bid->getAgencyWorkshop() ? DateHelper::getReadableDate($this->bid->getAgencyWorkshop()->contract_date) : '',
            '{bid_number}' => $this->bid->bid_number,
            '{bid_1c_number}' => $this->bid->bid_1C_number,
            '{bid_agency_number}' => $this->bid->bid_manufacturer_number,
            '{created_at}' => DateHelper::getReadableDate($this->bid->created_at),
            '{equipment}' => $this->bid->equipment_manufacturer,
            '{brand_model_name}' => $this->bid->brand_model_name,
            '{serial_number}' => $this->bid->serial_number,
            '{purchase_date}' => DateHelper::getReadableDate($this->bid->purchase_date),
            '{client_name}' => $this->bid->client_id ? $this->bid->client->name : '',
            '{client_phone}' => $this->bid->client_id ? $this->bid->client->clientPhone : '',
            '{condition}' => $this->bid->condition_manufacturer_name,
            '{defect_warranty}' => $this->bid->defect_manufacturer ?: $this->bid->defect,
            '{diagnostic_warranty}' => $this->bid->diagnostic_manufacturer ?: $this->bid->diagnostic,
            '{isWarranty}' => $this->bid->isWarranty() ? 'Да' : '',
            '{isPostPurchase}' => !$this->bid->isWarranty() ? 'Да' : '',
            '{defect}' => $this->bid->defect_manufacturer ?: $this->bid->defect,
            '{diagnostic}' => $this->bid->diagnostic_manufacturer ?: $this->bid->diagnostic,
            '{workshop_name}' => $this->bid->workshop_id ? $this->bid->workshop->name : '',
            '{workshop_address}' => $this->bid->workshop_id ? $this->bid->workshop->address : '',
            '{workshop_phone}' => $this->bid->workshop_id ? $this->bid->workshop->phone2 : '',
            '{workshop_mail}' => $this->bid->workshop_id ? $this->bid->workshop->email3 : '',
            '{saler_name}' => $this->bid->saler_name,
            '{job_nom_row}' => array_map(function($el) { return $el + 1; }, array_keys($jobs)),
            '{job_description}' => array_map(function(BidJob $job) {return $job->description;}, $jobs),
            '{job_price}' => array_map(function(BidJob $job) {return $job->priceConformed;}, $jobs),
            '{job_total_price}' => array_sum(array_map(function(BidJob $job) {return $job->priceConformed;}, $jobs)),
            '{spare_nom_row}' => array_map(function($el) { return $el + 1; }, array_keys($spares)),
            '{spare_name}' =>  array_map(function(Spare $spare) {return $spare->name;}, $spares),
            '{spare_vendor_code}' =>  array_map(function(Spare $spare) {return $spare->vendor_code;}, $spares),
            '{spare_quantity}' =>  array_map(function(Spare $spare) {return $spare->quantity;}, $spares),
            '{spare_price}' =>  array_map(function(Spare $spare) {return $spare->price;}, $spares),
            '{spare_total_price}' =>  array_map(function(Spare $spare) {return $spare->total_price;}, $spares),
            '{spare_quantity_all}' =>  array_sum(array_map(function(Spare $spare) {return $spare->quantity;}, $spares)),
            '{spare_total_price_all}' =>  array_sum(array_map(function(Spare $spare) {return $spare->total_price;}, $spares)),
        ];
    }
}