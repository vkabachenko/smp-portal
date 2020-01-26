<?php

namespace app\controllers;

use app\models\Bid;
use app\models\BidHistory;
use app\models\JobsCatalog;
use app\services\job\JobsCatalogService;
use Yii;
use app\models\BidJob;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

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
        $jobsCatalogService = new JobsCatalogService($bid->agency_id, $bid->created_at);
        $jobsCatalog = JobsCatalog::findOne(array_key_first($jobsCatalogService->jobsCatalogAsMap()));

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            BidHistory::createUpdated($bidId, $model, \Yii::$app->user->id, 'Создана запись о работе');
            $model->save();
            return $this->redirect(['index', 'bidId' => $bidId]);
        }

        return $this->render('create', [
            'model' => $model,
            'bid' => $bid,
            'jobsCatalog' => $jobsCatalog,
            'jobsCatalogService' => $jobsCatalogService
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $jobsCatalog = $model->jobsCatalog;
        $this->checkAccess('manageJobs', ['bidId' => $model->bid_id]);
        $bid = Bid::findOne($model->bid_id);
        $jobsCatalogService = new JobsCatalogService($bid->agency_id, $bid->created_at);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            BidHistory::createUpdated($model->bid_id, $model, \Yii::$app->user->id, 'Изменена запись о работе');
            $model->save();
            return $this->redirect(['index', 'bidId' => $model->bid_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'bid' => $bid,
            'jobsCatalog' => $jobsCatalog,
            'jobsCatalogService' => $jobsCatalogService
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
        BidHistory::createUpdated($model->bid_id, $model, \Yii::$app->user->id, 'Удалена запись о работе', false);
        $model->delete();

        return $this->redirect(['index', 'bidId' => $bidId]);
    }

    public function actionChangeJobsCatalog()
    {
        if (!\Yii::$app->request->isPost) {
            throw new MethodNotAllowedHttpException();
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;

        $model = JobsCatalog::findOne(\Yii::$app->request->post('id'));
        if (is_null($model)) {
            throw new NotFoundHttpException();
        }

        $this->checkAccess('manageJobsCatalog', ['agencyId' => $model->agency_id]);

        return [
            'vendor_code' => $model->vendor_code,
            'section_name' => $model->jobsSectionName,
            'hour_tariff' => $model->hour_tariff,
            'hours_required' => $model->hours_required,
            'price' => $model->price
        ];
    }
}
