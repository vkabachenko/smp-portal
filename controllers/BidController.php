<?php


namespace app\controllers;

use app\helpers\bid\CompositionHelper;
use app\models\BidAttribute;
use app\services\access\QueryRestrictionService;
use app\helpers\bid\TitleHelper;
use app\models\Bid;
use app\models\BidComment;
use app\models\BidHistory;
use app\models\Brand;
use app\models\BrandModel;
use app\models\form\CommentForm;
use app\models\form\MultipleUploadForm;
use app\models\search\BidSearch;
use app\templates\excel\act\ExcelAct;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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


    public function actionIndex()
    {
        $this->checkAccess('listBids');

        $restrictionService = new QueryRestrictionService(\Yii::$app->user->identity);
        $restrictions = $restrictionService->getRestrictions();

        $searchModel = new BidSearch(['restrictions' => $restrictions]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $this->checkAccess('createBid');

        $model = new Bid();
        $uploadForm = new MultipleUploadForm();
        $commentForm = new CommentForm();
        $hints = BidAttribute::getHints();

        if ($model->load(Yii::$app->request->post())) {
            $uploadForm->files = UploadedFile::getInstances($uploadForm, 'files');
            $commentForm->load(Yii::$app->request->post());
            if ($model->createBid(\Yii::$app->user->id, $uploadForm, $commentForm)) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'uploadForm' => $uploadForm,
            'commentForm' => $commentForm,
            'hints' => $hints
        ]);
    }

    public function actionUpdate($id)
    {
        $this->checkAccess('updateBid', ['bidId' => $id]);

        $model = Bid::findOne($id);
        $hints = BidAttribute::getHints();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->checkBrandCorrespondence();
            BidHistory::createUpdated($model, \Yii::$app->user->id);
            $model->flag_export = false;
            $model->save(false);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'hints' => $hints
        ]);
    }

    public function actionUpdateStatus($id)
    {
        $this->checkAccess('updateBidStatus', ['bidId' => $id]);

        $model = Bid::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            BidHistory::createUpdated($model, \Yii::$app->user->id);
            $model->flag_export = false;
            $model->save(false);
            return $this->redirect(['index']);
        }

        return $this->render('update-status', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $this->checkAccess('viewBid', ['bidId' => $id]);

        $model = Bid::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Page not found');
        }

        return $this->render('view', [
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