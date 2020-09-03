<?php


namespace app\templates\email;


use app\models\Bid;
use app\models\TemplateModel;

class EmailActTemplate extends EmailTemplate
{
    /* @var \app\models\Bid */
    private $bid;

    public function __construct($bidId, TemplateModel $template)
    {
        $this->bid = Bid::findOne($bidId);
        parent::__construct($template);
    }

    protected function getParams()
    {
        return [
            '{bid_number}' => $this->bid->bid_number,
            '{bid_1C_number}' => $this->bid->bid_1C_number,
            '{bid_manufacturer_number}' => $this->bid->bid_manufacturer_number,
            '{brand_name}' => $this->bid->brand_name,
            '{workshop_name}' => $this->bid->workshop->name,
        ];
    }

}