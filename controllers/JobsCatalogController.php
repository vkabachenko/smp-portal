<?php

namespace app\controllers;

use app\models\Agency;
use app\models\JobsCatalog;
use app\services\job\JobsCatalogService;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class JobsCatalogController  extends Controller
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


    public function actionIndex($agencyId)
    {
        $this->checkAccess('manageJobsCatalog', compact('agencyId'));
        $agency = Agency::findOne($agencyId);

        $service = new JobsCatalogService($agencyId, date('Y-m-d'));
        $query = $service->dateActualQuery();

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $this->render('index', [
            'agency' => $agency,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($agencyId)
    {
        $this->checkAccess('manageJobsCatalog', compact('agencyId'));
        $model = new JobsCatalog([
            'agency_id' => $agencyId,
            'date_actual' => '1970-01-01',
            'uuid' => \Yii::$app->security->generateRandomString()
        ]);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'agencyId' => $agencyId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('manageJobsCatalog', ['agencyId' => $model->agency_id]);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->bidJobs && $model->price != $model->getOldAttribute('price')) {
                $now = date('Y-m-d');
                if ($model->getOldAttribute('date_actual') >= $now) {
                    \Yii::$app->session->setFlash('error', 'Вид работвы на текущую дату уже задан');
                } else {
                    $agencyId = $model->agency_id;
                    $uuid = $model->uuid;
                    $model = new JobsCatalog([
                        'agency_id' => $agencyId,
                        'uuid' => $uuid,
                        'date_actual' => $now,
                    ]);
                    $model->load(\Yii::$app->request->post());
                    \Yii::$app->session->setFlash('info', 'Создана новая запись на текущую дату');
                }
            }
            $model->save();
            return $this->redirect(['index', 'agencyId' => $model->agency_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = JobsCatalog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $agencyId = $model->agency_id;
        $this->checkAccess('manageJobsCatalog', ['agencyId' => $agencyId]);
        if ($this->bidJob) {
            \Yii::$app->session->setFlash('error', 'Удаление невозможно! Существует запись о произведенной работе');
        } else {
            $model->delete();
            \Yii::$app->session->setFlash('success', 'Удалена запись о виде работ на текущую дату');
        }

        return $this->redirect(['index', 'agencyId' => $agencyId]);
    }


}