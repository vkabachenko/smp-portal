<?php


namespace app\controllers;

use app\models\search\BidHistorySearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\BidHistory;


class BidHistoryController  extends Controller
{
    use AccessTrait;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($bidId)
    {
        $this->checkAccess('viewBid', ['bidId' => $bidId]);

        $searchModel = new BidHistorySearch();
        $dataProvider = $searchModel->search($bidId);

        return $this->render('index', [
            'bidId' => $bidId,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        /* @var $model \app\models\BidHistory */
        $model = BidHistory::find()->where(['id' => $id])->one();
        $this->checkAccess('viewBid', ['bidId' => $model->bid_id]);

        return $this->render('view', [
            'model' => $model
        ]);
    }
}