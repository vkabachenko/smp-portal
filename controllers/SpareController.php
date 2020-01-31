<?php

namespace app\controllers;

use app\models\BidHistory;
use Yii;
use app\models\Spare;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SpareController extends Controller
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
        $this->checkAccess('viewSpare', compact('bidId'));
        $dataProvider = new ActiveDataProvider([
            'query' => Spare::find()->where(['bid_id' => $bidId])->orderBy('updated_at'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'bidId' => $bidId
        ]);
    }


    public function actionView($id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('viewSpare', ['bidId' => $model->bid_id]);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Spare::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreate($bidId)
    {
        $this->checkAccess('manageSpare', compact('bidId'));
        $model = new Spare(['bid_id' => $bidId]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            BidHistory::createUpdated($bidId, $model, \Yii::$app->user->id, 'Добавлена запчасть');
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'bidId' => $bidId
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('manageSpare', ['bidId' => $model->bid_id]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            BidHistory::createUpdated($model->bid_id, $model, \Yii::$app->user->id, 'Изменены данные о запчасти');
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $bidId = $model->bid_id;
        $this->checkAccess('manageSpare', ['bidId' => $model->bid_id]);
        BidHistory::createUpdated($model->bid_id, $model, \Yii::$app->user->id, 'Удалены данные запчасти', false);
        $model->delete();

        return $this->redirect(['index', 'bidId' => $bidId]);
    }
}
