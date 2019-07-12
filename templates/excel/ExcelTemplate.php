<?php
namespace app\templates\excel;

use app\models\Bid;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelTemplate
{
    /* @var \PhpOffice\PhpSpreadsheet\Spreadsheet */
    protected $spreadsheet;
    /* @var Worksheet */
    protected $activeSheet;
    /* @var \app\models\Bid */
    protected $bid;


    public function __construct($id)
    {
        $this->spreadsheet = new Spreadsheet();
        $this->activeSheet = $this->spreadsheet->getActiveSheet();
        $this->bid = Bid::findOne($id);
    }

    public function save()
    {
        $writer = new Xlsx($this->spreadsheet);
        $fileName = $this->getPath();
        $writer->save($fileName);
    }

    public function generate()
    {
        $this->setColumnSizes();
        $this->setRowSizes();
        $this->setMergedCells();
        $this->setBorders();
        $this->setAlignments();
        $this->setFonts();
        $this->setContents();
    }

    public function getPath()
    {
        return \Yii::getAlias('@webroot' . '/xls/' . 'act_' . $this->bid->id . '.xlsx');
    }

    protected function getActualColumnSizes()
    {
        return [
           // 'A' => 2,
        ];
    }

    protected function getActualRowSizes()
    {
        return [
            // 1 => 30,
        ];
    }

    protected function getActualMergedCells()
    {
        return [
            // 'B1:D1',
        ];
    }

    protected function getActualBorders()
    {
        return [
           // ['cells' => 'A1', 'direction' => 'left', 'style' => 'thin'],
           // 'direction' - не обязательно, по умолчанию all
           // 'style' - не обязательно, по умолчанию medium
        ];
    }

    protected function getActualAlignments()
    {
        return [
          //['cells' => 'A1', 'horizontal' => 'center', 'vertical' => 'bottom', 'wrap' => true]
          // если выравнивание не задано, остается по умолчанию
        ];
    }

    protected function getActualFonts()
    {
        return [
            //['cells' => 'A1', 'size' => 12, 'bold' => 'true', 'italic' => 'true', 'underline' => true]
            // если свойство не задано, остается по умолчанию
        ];
    }

    protected function getActualContents()
    {
        return [
            //'A1' => 'Content',
        ];
    }


    private function setColumnSizes()
    {
        foreach ($this->getActualColumnSizes() as $column => $size) {
            $this->activeSheet->getColumnDimension($column)->setWidth($size);
        }
    }

    private function setRowSizes()
    {
        foreach ($this->getActualRowSizes() as $row => $size) {
            $this->activeSheet->getRowDimension($row)->setRowHeight($size);
        }
    }

    private function setMergedCells()
    {
        foreach ($this->getActualMergedCells() as $cells) {
            $this->activeSheet->mergeCells($cells);
        }
    }

    private function setBorders()
    {
        foreach ($this->getActualBorders() as $item) {
            $borders = $this->activeSheet->getStyle($item['cells'])->getBorders();
            $direction = isset($item['direction']) ? $item['direction'] : 'all';
            switch ($direction) {
                case 'all':
                    $borders = $borders->getAllBorders();
                    break;
                case 'left':
                    $borders = $borders->getLeft();
                    break;
                case 'right':
                    $borders = $borders->getRight();
                    break;
                case 'top':
                    $borders = $borders->getTop();
                    break;
                case 'bottom':
                    $borders = $borders->getBottom();
                    break;
                default:
                    $borders = $borders->getAllBorders();
            }
            $style = isset($item['style']) ? $item['style'] : Border::BORDER_MEDIUM;
            $borders->setBorderStyle($style);
        }
    }

    private function setAlignments()
    {
        foreach ($this->getActualAlignments() as $item) {
            $alignment = $this->activeSheet->getStyle($item['cells'])->getAlignment();
            if (isset($item['horizontal'])) {
                $alignment = $alignment->setHorizontal($item['horizontal']);
            }
            if (isset($item['vertical'])) {
                $alignment = $alignment->setVertical($item['vertical']);
            }
            if (isset($item['wrap'])) {
                $alignment->setWrapText(true);
            }
        }
    }

    private function setFonts()
    {
        foreach ($this->getActualFonts() as $item) {
            $font = $this->activeSheet->getStyle($item['cells'])->getFont();
            if (isset($item['size'])) {
                $font = $font->setSize($item['size']);
            }
            if (isset($item['bold'])) {
                $font = $font->setBold($item['bold']);
            }
            if (isset($item['italic'])) {
                $font = $font->setItalic($item['italic']);
            }
            if (isset($item['underline'])) {
                $font->setUnderline($item['underline']);
            }
        }
    }

    private function setContents()
    {
        foreach ($this->getActualContents() as $cell => $content) {
            $this->activeSheet->setCellValueExplicit($cell, $content, DataType::TYPE_STRING);
        }
    }
}