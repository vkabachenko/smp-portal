<?php

namespace app\controllers;

use app\models\Bid;
use Yii;
use app\models\AutoFillAttributes;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AutoFillAttributesController implements the CRUD actions for AutoFillAttributes model.
 */
class AutoFillAttributesController extends Controller
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

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AutoFillAttributes::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new AutoFillAttributes();

        $autoFilledAttributes = Bid::autoFilledAttributes();
        $model->auto_fill = array_fill_keys(array_keys($autoFilledAttributes), null);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $autoFilledAttributes = Bid::autoFilledAttributes();

        $autoFillInModel = $model->auto_fill ?: [];
        $autoFillEmpty = array_fill_keys(array_keys($autoFilledAttributes), null);

        $model->auto_fill = array_merge($autoFillEmpty,
            array_filter($autoFillInModel, function($attribute) use ($autoFillEmpty) {
                return in_array($attribute, array_keys($autoFillEmpty));
            },  ARRAY_FILTER_USE_KEY)
        );

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = AutoFillAttributes::findOne($id)) !== null) {
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
