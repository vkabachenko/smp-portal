<?php


namespace app\templates\email;


use app\models\Bid;
use app\models\Report;
use app\models\TemplateModel;

class EmailReportTemplate extends EmailTemplate
{
    /* @var \app\models\Report */
    private $report;

    public function __construct(Report $report, TemplateModel $template)
    {
        $this->report = $report;
        parent::__construct($template);
    }

    protected function getParams()
    {
        return [
            '{workshop_name}' => $this->report->workshop->name,
        ];
    }

}