<?php


namespace app\models\search;


use app\models\BidHistory;
use yii\data\ActiveDataProvider;

class BidHistorySearch
{
    public function search($bidId)
    {
        $query = BidHistory::find()->where(['bid_id' => $bidId])->orderBy('created_at DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}