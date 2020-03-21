<?php

namespace app\models;

trait BidAttributesTrait
{
    public function getBidAttributes($attributeName)
    {
        return is_null($this->getModel()->$attributeName) ? [] : $this->getModel()->$attributeName;
    }

    abstract protected function getModel();
}