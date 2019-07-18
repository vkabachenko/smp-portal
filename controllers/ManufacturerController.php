<?php

namespace app\controllers;

use app\models\form\UploadExcelTemplateForm;
use Yii;
use app\models\Manufacturer;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


class ManufacturerController extends Controller
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
                            return \Yii::$app->user->can('manageCatalogs');
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

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Manufacturer::find()->orderBy('name'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Manufacturer();

        $uploadForm = new UploadExcelTemplateForm();
        if ($model->load(Yii::$app->request->post()) && $model->saveWithUpload($uploadForm)) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'uploadForm' => $uploadForm
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $uploadForm = new UploadExcelTemplateForm();
        if ($model->load(Yii::$app->request->post()) && $model->saveWithUpload($uploadForm)) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'uploadForm' => $uploadForm
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Manufacturer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
