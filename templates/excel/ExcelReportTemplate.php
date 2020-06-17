<?php
namespace app\templates\excel;

use app\models\Bid;
use app\models\BidStatus;

abstract class ExcelReportTemplate extends ExcelTemplate
{
    /* @var \app\models\Bid[] */
    public $bids;

    protected function getAttributeParams($attribute, $children, $isParentAttribute, callable $callback = null)
    {
        $param = [];
        foreach ($this->bids as $bid) {
            if (empty($bid->$children)) {
                $param[] = $this->getAttributeParam($isParentAttribute ? $bid->$attribute : '', $callback);
            } else {
                foreach ($bid->$children as $child) {
                    $param[] = $this->getAttributeParam($isParentAttribute ? $bid->$attribute : $child->$attribute, $callback);
                }
            }
        }
        return $param;
    }

    protected function getAttributeParam($param, callable $callback = null)
    {
        return $callback ? $callback($param) : $param;
    }

}