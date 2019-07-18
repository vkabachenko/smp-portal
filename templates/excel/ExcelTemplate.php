<?php
namespace app\templates\excel;

use app\models\Bid;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class ExcelTemplate
{
    /* @var \app\models\Bid */
    public $bid;

    public function __construct($id)
    {
        $this->bid = Bid::findOne($id);
    }

    public function getPath()
    {
        return $this->getDirectory() . $this->getFilename();
    }

    protected function getParams() {
        return [];
    }

    public function isGenerated() {
        return is_file($this->getPath());
    }

    public abstract function generate();

    public abstract function getDirectory();

    public abstract function getFilename();
}