<?php
namespace app\templates\excel;

use app\models\Bid;
use app\models\BidStatus;

abstract class ExcelReportTemplate extends ExcelTemplate
{
    /* @var \app\models\Bid[] */
    public $bids;
    public $agencyId;

    public function __construct($agencyId)
    {
        $this->agencyId = $agencyId;
    }

    public function getBids($dateFrom, $dateTo)
    {
        $this->bids = Bid::find()
            ->with(['spares', 'jobs'])
            ->where(['between', 'created_at', $dateFrom, $dateTo])
            ->andWhere(['agency_id' => $this->agencyId])
            ->andWhere(['status_id' => BidStatus::getId(BidStatus::STATUS_DONE)])
            ->orderBy('created_at')
            ->all();
    }

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