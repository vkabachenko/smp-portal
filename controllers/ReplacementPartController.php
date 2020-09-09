<?php


namespace app\controllers;


use app\models\BidHistory;
use app\models\ReplacementPart;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ReplacementPartController extends Controller
{
    use AccessTrait;

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
        $this->checkAccess('manageReplacementParts', compact('bidId'));
        $dataProvider = new ActiveDataProvider([
            'query' => ReplacementPart::find()->where(['bid_id' => $bidId])->orderBy('num_order'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'bidId' => $bidId
        ]);
    }

    public function actionView($id, $returnUrl)
    {
        $model = $this->findModel($id);
        $this->checkAccess('manageReplacementParts', ['bidId' => $model->bid_id]);

        return $this->render('view', compact('model', 'returnUrl'));
    }

    public function actionCreate($bidId)
    {
        $this->checkAccess('manageReplacementParts', compact('bidId'));
        $model = new ReplacementPart(['bid_id' => $bidId]);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            BidHistory::createUpdated($bidId, $model, \Yii::$app->user->id, 'Создана запись об артикуле для сервиса');
            $model->save();
            return $this->redirect(['index', 'bidId' => $bidId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateModal($bidId)
    {
        $this->checkAccess('manageReplacementParts', compact('bidId'));
        $model = new ReplacementPart(['bid_id' => $bidId]);

        if ($model->load(\Yii::$app->request->post())) {
            if (!$model->save()) {
                \Yii::error($model->getErrors());
            }
            BidHistory::createUpdated($bidId, $model, \Yii::$app->user->id, 'Создана запись об артикуле для сервиса');
        }
        return $this->redirect(['bid/view', 'id' => $bidId]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('manageReplacementParts', ['bidId' => $model->bid_id]);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            BidHistory::createUpdated($model->bid_id, $model, \Yii::$app->user->id, 'Изменена запись об артикуле для сервиса');
            $model->save();
            return $this->redirect(['index', 'bidId' => $model->bid_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = ReplacementPart::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $bidId = $model->bid_id;
        $this->checkAccess('manageReplacementParts', ['bidId' => $model->bid_id]);
        BidHistory::createUpdated($model->bid_id, $model, \Yii::$app->user->id, 'Удалена запись об артикуле для сервиса', false);
        $model->delete();

        return $this->redirect(['index', 'bidId' => $bidId]);
    }

}