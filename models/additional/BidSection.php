<?php

namespace app\models\additional;

use app\models\Bid;

class BidSection
{
    const ATTRIBUTE_SECTION_1 = 'bid_attributes_section1';
    const ATTRIBUTE_SECTION_2 = 'bid_attributes_section2';
    const ATTRIBUTE_SECTION_3 = 'bid_attributes_section3';
    const ATTRIBUTE_SECTION_4 = 'bid_attributes_section4';
    const ATTRIBUTE_SECTION_5 = 'bid_attributes_section5';

    public $section1 = [];
    public $section2 = [];
    public $section3 = [];
    public $section4 = [];
    public $section5 = [];

    public static function callbackSortable($attribute) {
        return [
            'content' => Bid::getAllAttributes()[$attribute],
            'options' => ['data-attribute' => $attribute]
        ];
    }

    public function adaptSections($availableAttributes)
    {
        $this->section1 = array_intersect($this->section1, $availableAttributes);
        $this->section2 = array_intersect($this->section2, $availableAttributes);
        $this->section3 = array_intersect($this->section3, $availableAttributes);
        $this->section4 = array_intersect($this->section4, $availableAttributes);
        $this->section5 = array_intersect($this->section5, $availableAttributes);

        $diff = array_diff($availableAttributes, $this->section1, $this->section2, $this->section3, $this->section4, $this->section5);
        $this->section1 = array_merge($this->section1, $diff);
    }
}