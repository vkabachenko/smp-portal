<?php


namespace app\controllers;

use app\models\Bid;
use app\models\BidComment;
use app\models\BidCommentsRead;
use app\models\search\BidCommentSearch;
use yii\filters\AccessControl;
use yii\web\Controller;

class BidCommentController  extends Controller
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
        $this->checkAccess('viewComments');

        $searchModel = new BidCommentSearch();
        $dataProvider = $searchModel->search($bidId);
        BidCommentsRead::createOrUpdate($bidId);

        return $this->render('index', [
            'bidId' => $bidId,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($bidId)
    {
        $this->checkAccess('createComment');

        $model = new BidComment([
            'bid_id' => $bidId,
            'user_id' => \Yii::$app->user->id
        ]);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            Bid::setFlagExport($bidId, false);
            BidCommentsRead::createOrUpdate($bidId);
            return $this->redirect(['index', 'bidId' => $bidId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

}