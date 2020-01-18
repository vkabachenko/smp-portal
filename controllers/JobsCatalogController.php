<?php

namespace app\controllers;

use app\models\Agency;
use app\models\JobsCatalog;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
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
        ];
    }

    public function actionIndex($agencyId)
    {
        $this->checkAccess('manageJobsCatalog', compact('agencyId'));
        $agency = Agency::findOne($agencyId);
        //$dateActual = $dateActual ?: date('Y-m-d', time());
        $query = JobsCatalog::find()
            -> where(['agency_id' => $agencyId])
            -> orderBy('date_actual');
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $this->render('index', [
            'agency' => $agency,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($agencyId)
    {
        $this->checkAccess('manageJobsCatalog', compact('agencyId'));
        $model = new JobsCatalog(['agency_id' => $agencyId]);

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

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
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
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'agencyId' => $agencyId]);
    }

}