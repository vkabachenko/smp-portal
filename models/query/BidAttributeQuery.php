<?php

namespace app\models\query;

use app\models\Bid;
use yii\db\ActiveQuery;

class BidAttributeQuery extends ActiveQuery
{
    public function active()
    {
        return $this
            ->where(['attribute' => array_keys(Bid::EDITABLE_ATTRIBUTES)]);
    }
}
