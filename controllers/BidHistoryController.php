<?php


namespace app\controllers;


use app\models\search\BidHistorySearch;
use yii\filters\AccessControl;
use yii\web\Controller;

class BidHistoryController  extends Controller
{
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
                        'roles' => ['master'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($bidId)
    {
        $searchModel = new BidHistorySearch();
        $dataProvider = $searchModel->search($bidId);

        return $this->render('index', [
            'bidId' => $bidId,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($bidId)
    {
        $this->redirect(['index', 'bidId' => $bidId ]);
    }
}