<?php


namespace app\models\search;

use app\models\Spare;
use yii\data\ActiveDataProvider;

class SpareSearch
{
    public function search($bidId)
    {
        $query = Spare::find()->where(['bid_id' => $bidId]);

        switch(\Yii::$app->user->identity->role) {
            case 'manager':
                $query->andWhere(['is_paid' => false]);
                break;
            case 'master':
                if (!\Yii::$app->user->identity->master->workshop->canManagePaidBid()) {
                    $query->andWhere(['is_paid' => false]);
                }
                break;
        }

        $query->orderBy('updated_at');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}