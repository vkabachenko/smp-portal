<?php

namespace app\models\query;

use yii\db\ActiveQuery;

class NewsQuery extends ActiveQuery
{
    public function published()
    {
        return $this
            ->where(['active' => true])
            ->orderBy('updated_at DESC');
    }
}
