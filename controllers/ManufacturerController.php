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


class ManufacturerController extends Controller
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

    public function actionIndex()
    {
        $this->checkAccess('manageBrand');

        $dataProvider = new ActiveDataProvider([
            'query' => Manufacturer::find()->orderBy('name'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexTemplate()
    {
        $this->checkAccess('manageBrand');

        $dataProvider = new ActiveDataProvider([
            'query' => Manufacturer::find()->orderBy('name'),
        ]);

        return $this->render('index-template', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $this->checkAccess('manageCatalogs');

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
        $this->checkAccess('manageCatalogs');

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

    public function actionUpdateTemplate($id)
    {
        $this->checkAccess('manageBrand');

        $model = $this->findModel($id);

        $uploadForm = new UploadExcelTemplateForm();
        if (\Yii::$app->request->isPost && $model->saveWithUpload($uploadForm)) {
            return $this->redirect(['index-template']);
        }

        return $this->render('update-template', [
            'model' => $model,
            'uploadForm' => $uploadForm
        ]);
    }

    public function actionDelete($id)
    {
        $this->checkAccess('manageCatalogs');
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
