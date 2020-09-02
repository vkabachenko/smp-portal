<?php
namespace app\templates\excel;

use app\models\Bid;
use app\models\TemplateModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class ExcelActTemplate extends ExcelTemplate
{
    /* @var \app\models\Bid */
    public $bid;

    /* @var \app\models\TemplateModel */
    public $template;

    public function __construct($id, $subType)
    {
        $this->bid = Bid::findOne($id);
        $this->template = TemplateModel::find()
        ->where(['agency_id' => $this->bid->getAgency()->id, 'type' => TemplateModel::TYPE_ACT, 'sub_type' => $subType])
        ->one();
    }
}