<?php


namespace app\controllers;

use app\models\Bid;
use app\models\search\BidHistorySearch;
use app\models\search\BidSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class BidController extends Controller
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


    public function actionIndex($title = 'Список заявок')
    {
        $this->checkAccess('listBids');

        $searchModel = new BidSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title' => $title
        ]);
    }

    public function actionCreate()
    {
        $this->checkAccess('createBid');

        $model = new Bid();

        if ($model->load(Yii::$app->request->post()) && $model->createBid(\Yii::$app->user->id)) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $this->checkAccess('updateBid');

        $model = Bid::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

}