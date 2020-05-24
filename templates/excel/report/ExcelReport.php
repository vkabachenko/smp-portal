<?php
namespace app\templates\excel\report;

use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use app\models\Bid;
use app\models\Report;
use app\templates\excel\ExcelReportTemplate;


class ExcelReport extends ExcelReportTemplate
{
    /**
     * @var Report
     */
    private $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
        $this->getBids();
    }

    public function getFilename()
    {
        return 'report_' . $this->report->agency_id . '_' . $this->report->report_date . '_' . $this->report->report_nom . '.xlsx';
    }

    public function getDirectory()
    {
        return \Yii::getAlias('@app/templates/excel/report/generated/');
    }

    public function generate()
    {
        $agency = $this->report->agency;
        if (!is_null($agency->report_template)) {
            PhpExcelTemplator::saveToFile($agency->getTemplatePath('report'), $this->getPath(), $this->getParams());
        }
    }

    protected function getBids()
    {
        $this->bids = $this->report->isNewRecord
            ? Bid::find()->where(['bid_number' => $this->report->selectedBids])->all()
            : $this->report->bids;
    }

    protected function getParams()
    {
        return [
            '{report_no}' => $this->report->report_nom,
            '{report_date}' => $this->report->report_date,
            '{report_date_from}' => '',
            '{report_date_to}' => '',
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