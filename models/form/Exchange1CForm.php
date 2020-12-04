<?php

namespace app\models\form;


use app\models\Bid;
use yii\base\Model;

class Exchange1CForm  extends Model
{
    public $attributes;

    public function init()
    {
        $this->attributes = array_filter($this->attributes, function($k) {
            return isset(Bid::EXCHANGE_1C_ATTRIBUTES[$k]);
        }, ARRAY_FILTER_USE_KEY);
        $this->attributes = array_merge(
            array_fill_keys(array_keys(Bid::EXCHANGE_1C_ATTRIBUTES), ''),
            $this->attributes
        );
    }


    public function rules()
    {
        return [
            [['attributes'], 'safe']
        ];
    }

}