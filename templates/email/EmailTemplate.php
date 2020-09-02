<?php


namespace app\templates\email;


use app\models\Bid;

class EmailTemplate
{
    /* @var \app\models\Bid */
    private $bid;

    public function __construct($bidId)
    {
        $this->bid = Bid::findOne($bidId);
    }


    public function getText($text)
    {
        return str_replace(array_keys($this->getParams()), array_values($this->getParams()), $text);
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