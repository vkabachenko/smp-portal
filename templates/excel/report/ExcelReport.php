<?php
namespace app\templates\excel\report;

use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use app\models\Agency;
use app\models\form\ReportForm;
use app\templates\excel\ExcelReportTemplate;
use app\helpers\common\DateHelper;

class ExcelReport extends ExcelReportTemplate
{
    /**
     * @var ReportForm
     */
    private $reportForm;

    public function __construct($agencyId, ReportForm $reportForm)
    {
        parent::__construct($agencyId);

        $this->reportForm = $reportForm;
        $this->getBids(DateHelper::convert($reportForm->dateFrom), DateHelper::convert($reportForm->dateTo));
    }

    public function getFilename()
    {
        return 'report_' . $this->agencyId . '_' . $this->reportForm->dateFrom . '_' . $this->reportForm->dateTo . '.xlsx';
    }

    public function getDirectory()
    {
        return \Yii::getAlias('@app/templates/excel/report/generated/');
    }

    public function generate()
    {
        $agency = Agency::findOne($this->agencyId);
        if (!is_null($agency->report_template)) {
            PhpExcelTemplator::saveToFile($agency->getTemplatePath('report'), $this->getPath(), $this->getParams());
        }
    }

    protected function getParams()
    {
        return [
            '{report_no}' => $this->reportForm->reportNom,
            '{report_date}' => $this->reportForm->reportDate,
            '{report_date_from}' => $this->reportForm->dateFrom,
            '{report_date_to}' => $this->reportForm->dateTo,
            '{spare_nom_row}' => array_map(
                function($el) { return $el + 1; },
                array_keys($this->getAttributeParams('bid_number', 'spares', true))
            ),
            '{spare_bid_number}' => $this->getAttributeParams(
                'bid_number',
                'spares',
                true
            ),
            '{spare_bid_equipment}' => $this->getAttributeParams(
                'equipment',
                'spares',
                true
            ),
            '{spare_bid_serial_number}' => $this->getAttributeParams(
                'serial_number',
                'spares',
                true
            ),
            '{spare_purchase_date}' => $this->getAttributeParams(
                'purchase_date',
                'spares',
                true,
                'app\helpers\common\DateHelper::getReadableDate'
            ),
            '{spare_date_completion}' => $this->getAttributeParams(
                'date_completion',
                'spares',
                true,
                'app\helpers\common\DateHelper::getReadableDate'
            ),
            '{spare_name}' => $this->getAttributeParams(
                'name',
                'spares',
                false
            ),
            '{spare_vendor_code}' => $this->getAttributeParams(
                'vendor_code',
                'spares',
                false
            ),
            '{spare_quantity}' => $this->getAttributeParams(
                'quantity',
                'spares',
                false
            ),
            '{spare_price}' => $this->getAttributeParams(
                'price',
                'spares',
                false
            ),
            '{spare_total_price}' => $this->getAttributeParams(
                'total_price',
                'spares',
                false
            ),
            '{spare_quantity_all}' => array_sum($this->getAttributeParams(
                'quantity',
                'spares',
                false
            )),
            '{spare_total_price_all}' => array_sum($this->getAttributeParams(
                'total_price',
                'spares',
                false
            )),
            '{job_nom_row}' => array_map(
                function($el) { return $el + 1; },
                array_keys($this->getAttributeParams('bid_number', 'jobs', true))
            ),
            '{job_bid_number}' => $this->getAttributeParams(
                'bid_number',
                'jobs',
                true
            ),
            '{job_bid_equipment}' => $this->getAttributeParams(
                'equipment',
                'jobs',
                true
            ),
            '{job_bid_serial_number}' => $this->getAttributeParams(
                'serial_number',
                'jobs',
                true
            ),
            '{job_purchase_date}' => $this->getAttributeParams(
                'purchase_date',
                'jobs',
                true,
                'app\helpers\common\DateHelper::getReadableDate'
            ),
            '{job_date_completion}' => $this->getAttributeParams(
                'date_completion',
                'jobs',
                true,
                'app\helpers\common\DateHelper::getReadableDate'
            ),
            '{job_description}' => $this->getAttributeParams(
                'description',
                'jobs',
                false
            ),
            '{job_price}' => $this->getAttributeParams(
                'priceConformed',
                'jobs',
                false
            ),
            '{job_price_all}' => array_sum($this->getAttributeParams(
                'priceConformed',
                'jobs',
                false
            )),
            '{report_total_price}' => array_sum($this->getAttributeParams(
                'priceConformed',
                'jobs',
                false
            )) +
            array_sum($this->getAttributeParams(
                'total_price',
                'spares',
                false
            )),
        ];
    }

}