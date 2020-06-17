<?php


namespace app\models\search;


use app\models\BidComment;
use yii\data\ActiveDataProvider;

class BidCommentSearch
{
    public function search($bidId)
    {
        $query = BidComment::find()->where(['bid_id' => $bidId])->orderBy('created_at');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}