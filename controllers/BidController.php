<?php


namespace app\controllers;

use app\helpers\bid\CompositionHelper;
use app\models\Bid;
use app\models\Brand;
use app\models\BrandModel;
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

    public function actionBrand()
    {
        $this->checkAccess('createBid');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = \Yii::$app->request->post();

        $query = Brand::find()
            ->select(['id', 'name', 'manufacturer_id'])
            ->andWhere(['like', 'name', $data['term']]);

        if (!empty($data['manufacturerId'])) {
            $query->andWhere(['manufacturer_id' => intval($data['manufacturerId'])]);
        }

        $brands = $query->asArray()->all();

        return ['brands' => $brands];
    }

    public function actionBrandModel()
    {
        $this->checkAccess('createBid');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = \Yii::$app->request->post();

        if (empty($data['brandId'])) {
            $brandModels = [];
        } else {
            $brandModels = BrandModel::find()
                ->select(['id', 'name'])
                ->andWhere(['like', 'name', $data['term']])
                ->andWhere(['brand_id' => intval($data['brandId'])])
                ->all();
        }

        return ['brandModels' => $brandModels];
    }

    public function actionComposition()
    {
        $this->checkAccess('createBid');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = \Yii::$app->request->post();

        return ['compositions' => CompositionHelper::unionCompositions($data['brandId'], $data['term'])];
    }

}