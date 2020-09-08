<?php


namespace app\models\search;

use app\models\Spare;
use yii\data\ActiveDataProvider;

class SpareSearch
{
    public function search($bidId)
    {
        $query = Spare::find()->where(['bid_id' => $bidId]);

        $query->orderBy('updated_at');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}