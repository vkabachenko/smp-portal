<?php

namespace app\controllers;

use vova07\imperavi\actions\UploadFileAction;
use Yii;
use app\models\BidAttribute;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BidAttributeController implements the CRUD actions for BidAttribute model.
 */
class BidAttributeController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
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

    public function actions()
    {
        return [
            'image-upload' => [
                'class' => UploadFileAction::class,
                'url' => Url::to('@web/uploads/hints/'),
                'path' => '@webroot/uploads/hints',
            ],
        ];
    }


    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BidAttribute::find()->active(),
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new BidAttribute();

        if ($model->load(Yii::$app->request->post())
            && !\Yii::$app->request->post('is_html_description_revert')
            && $model->save()
        ) {
            return $this->redirect(['index']);
        }

        $model->description = null;

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())
            && !\Yii::$app->request->post('is_html_description_revert')
            && $model->save()
        ) {
            return $this->redirect(['index']);
        }

        if (\Yii::$app->request->post('is_html_description_revert')) {
            $model->description = null;
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = BidAttribute::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

}
