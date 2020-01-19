<?php

namespace app\controllers;

use app\models\Bid;
use Yii;
use app\models\BidJob;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BidJobController implements the CRUD actions for BidJob model.
 */
class BidJobController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($bidId)
    {
        $this->checkAccess('manageJobs', compact('bidId'));
        $dataProvider = new ActiveDataProvider([
            'query' => BidJob::find()->where(['bid_id' => $bidId])->orderBy('updated_at'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'bidId' => $bidId
        ]);
    }

    public function actionCreate($bidId)
    {
        $this->checkAccess('manageJobs', compact('bidId'));
        $bid = Bid::findOne($bidId);
        $model = new BidJob(['bid_id' => $bidId]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'bidId' => $bidId]);
        }

        return $this->render('create', [
            'model' => $model,
            'bid' => $bid
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('manageJobs', ['bidId' => $model->bid_id]);
        $bid = Bid::findOne($model->bid_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'bidId' => $model->bid_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'bid' => $bid
        ]);
    }

    protected function findModel($id)
    {
        if (($model = BidJob::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $bidId = $model->bid_id;
        $this->checkAccess('manageJobs', ['bidId' => $model->bid_id]);
        $model->delete();

        return $this->redirect(['index', 'bidId' => $bidId]);
    }
}
