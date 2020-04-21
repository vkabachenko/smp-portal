<?php
namespace app\templates\excel;

use app\models\Bid;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class ExcelActTemplate extends ExcelTemplate
{
    /* @var \app\models\Bid */
    public $bid;

    public function __construct($id)
    {
        $this->bid = Bid::findOne($id);
    }
}