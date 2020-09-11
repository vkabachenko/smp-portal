<?php


namespace app\models\search;


use app\models\BidComment;
use yii\data\ActiveDataProvider;

class BidCommentSearch
{
    public function search($bidId, $private)
    {
        $query = BidComment::find()->where(['bid_id' => $bidId]);
        $query->andWhere(['private' => $private]);

        $query->orderBy('created_at');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}