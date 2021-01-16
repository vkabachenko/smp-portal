<?php


namespace app\templates\email;


use app\helpers\common\DateHelper;
use app\models\Bid;
use app\models\DecisionStatusInterface;
use app\models\TemplateModel;

class EmailActTemplate extends EmailTemplate
{
    /* @var \app\models\Bid */
    private $bid;

    public function __construct(Bid $bid, $template, DecisionStatusInterface $decision)
    {
        parent::__construct($template, $decision);
        $this->bid = $bid;
    }

    protected function getParams()
    {
        return [
            '{bid_number}' => $this->bid->bid_number,
            '{bid_1c_number}' => $this->bid->bid_1C_number,
            '{bid_manufacturer_number}' => $this->bid->bid_manufacturer_number,
            '{brand_name}' => $this->bid->brand_name,
            '{workshop_name}' => $this->bid->workshop->name,
            '{contract_nom}' => $this->bid->getAgencyWorkshop() ? $this->bid->getAgencyWorkshop()->contract_nom : '',
            '{contract_date}' => $this->bid->getAgencyWorkshop() ? DateHelper::getReadableDate($this->bid->getAgencyWorkshop()->contract_date) : '',
        ];
    }

}