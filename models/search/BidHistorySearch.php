<?php


namespace app\models\search;


use app\models\BidHistory;
use yii\data\ActiveDataProvider;

class BidHistorySearch
{
    public function search($bidId)
    {
        $query = BidHistory::find()->where(['bid_id' => $bidId]);

        if (\Yii::$app->user->identity->role == 'manager') {
            $query
                ->joinWith('user', false)
                ->andWhere(['OR',
                             ['user.role' => 'manager'],
                             ['IN', 'action', [BidHistory::CREATED, BidHistory::IMPORTED_1C]],
                           ]
                );
        }

        $query->orderBy('bid_history.created_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}