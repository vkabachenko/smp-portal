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

    public function actionIndex($bidId, $private)
    {
        $this->checkAccess('viewComments');

        $searchModel = new BidCommentSearch();
        $dataProvider = $searchModel->search($bidId, $private);
        BidCommentsRead::createOrUpdate($bidId);

        return $this->render('index', [
            'bidId' => $bidId,
            'dataProvider' => $dataProvider,
            'private' => $private
        ]);
    }

    public function actionCreate($bidId, $private)
    {
        $this->checkAccess('createComment');

        $model = new BidComment([
            'bid_id' => $bidId,
            'user_id' => \Yii::$app->user->id,
            'private' => $private
        ]);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            Bid::setFlagExport($bidId, false);
            BidCommentsRead::createOrUpdate($bidId);
            return $this->redirect(['index', 'bidId' => $bidId, 'private' => $private]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->checkAccess('updateComment', ['commentId' => $id]);

        $model = BidComment::findOne($id);
        $bidId = $model->bid->id;

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            Bid::setFlagExport($bidId, false);
            BidCommentsRead::createOrUpdate($bidId);
            return $this->redirect(['index', 'bidId' => $bidId, 'private' => $model->private]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);

    }

}