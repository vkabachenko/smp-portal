<?php

namespace app\controllers;

use Yii;
use app\models\Brand;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class BrandController extends Controller
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
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('manageBrand');
                        }
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

    public function actionIndex($manufacturerId)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Brand::find()->where(['manufacturer_id' => $manufacturerId])->orderBy('name'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'manufacturerId' => $manufacturerId
        ]);
    }

    public function actionCreate($manufacturerId)
    {
        $model = new Brand(['manufacturer_id' => $manufacturerId]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'manufacturerId' => $manufacturerId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'manufacturerId' => $model->manufacturer_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $manufacturerId = $model->manufacturer_id;
        $model->delete();

        return $this->redirect(['index', 'manufacturerId' => $manufacturerId]);
    }

    protected function findModel($id)
    {
        if (($model = Brand::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
