<?php

namespace app\models;

use app\models\additional\BidSection;

trait BidAttributesTrait
{
    abstract protected function getModel();

    public function getBidAttributes($attributeName)
    {
        return is_null($this->getModel()->$attributeName) ? [] : $this->getModel()->$attributeName;
    }

    public function getAvailableAttributes($withAlwaysVisible = false)
    {
        $ownAttributes = $this->getBidAttributes('bid_attributes');
        $availableAttributes = array_diff(
            BidAttribute::getAvailableAttributes($this->getModel()->getCommonHiddenAttributeName(), $withAlwaysVisible),
            $ownAttributes);

        return $availableAttributes;
    }

    public function getSectionsAttributes()
    {
        $bidSection = new BidSection();
        $availableAttributes = $this->getAvailableAttributes(true);
        $bidSection->section1 = $this->getBidAttributes(BidSection::ATTRIBUTE_SECTION_1);
        $bidSection->section2 = $this->getBidAttributes(BidSection::ATTRIBUTE_SECTION_2);
        $bidSection->section3 = $this->getBidAttributes(BidSection::ATTRIBUTE_SECTION_3);
        $bidSection->section4 = $this->getBidAttributes(BidSection::ATTRIBUTE_SECTION_4);
        $bidSection->section5 = $this->getBidAttributes(BidSection::ATTRIBUTE_SECTION_5);
        $bidSection->adaptSections($availableAttributes);

        return $bidSection;
    }


}