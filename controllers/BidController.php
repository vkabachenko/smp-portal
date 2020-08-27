<?php


namespace app\controllers;

use app\helpers\bid\CompositionHelper;
use app\helpers\bid\GridHelper;
use app\helpers\bid\ViewHelper;
use app\models\BidAttribute;
use app\models\BidJob;
use app\models\BidJob1c;
use app\models\BidStatus;
use app\models\ClientProposition;
use app\models\form\SendActForm;
use app\models\ReplacementPart;
use app\models\Spare;
use app\models\User;
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
use app\services\status\DoneStatusService;
use app\services\status\ReadStatusService;
use app\templates\excel\act\ExcelAct;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
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

        $gridAttributes = \Yii::$app->user->identity->grid_attributes ?
            Json::decode(\Yii::$app->user->identity->grid_attributes)
            : Bid::GRID_ATTRIBUTES;
        $gridAttributes = array_filter($gridAttributes, function($attribute) {
            return \Yii::$app->user->can('adminBidAttribute', ['attribute' => $attribute]);
        }, ARRAY_FILTER_USE_KEY);

        $gridHelper = new GridHelper($gridAttributes, $searchModel);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'gridHelper' => $gridHelper
        ]);
    }

    public function actionSetGridAttributes()
    {
        $gridAttributes = \Yii::$app->user->identity->grid_attributes ?
            Json::decode(\Yii::$app->user->identity->grid_attributes)
            : Bid::GRID_ATTRIBUTES;
        $gridAttributes = array_filter($gridAttributes, function($attribute) {
            return \Yii::$app->user->can('adminBidAttribute', ['attribute' => $attribute]);
        }, ARRAY_FILTER_USE_KEY);

        return $this->render('set-grid-attributes', compact('gridAttributes'));
    }

    public function actionSaveGridAttributes()
    {
        $user = User::findOne(\Yii::$app->user->id);
        $gridAttributes = \Yii::$app->request->post('grid_attributes');
        $user->grid_attributes = Json::encode($gridAttributes);
        $user->save();

        return $this->redirect(['index']);
    }

    public function actionCreate()
    {
        $this->checkAccess('createBid');

        $model = new Bid([
            'user_id' => \Yii::$app->user->id,
            'master_id' => \Yii::$app->user->identity->master ? \Yii::$app->user->identity->master->id : null,
            'workshop_id' => \Yii::$app->user->identity->master ? \Yii::$app->user->identity->master->workshop_id : null,
            'application_date' => date('Y-m-d')
        ]);
        $uploadForm = new MultipleUploadForm();
        $hints = BidAttribute::getHints();

        if ($model->load(Yii::$app->request->post())) {
            $uploadForm->load(\Yii::$app->request->post());
            if ($model->createBid(\Yii::$app->user->id, $uploadForm)) {
                $model->bid_number = strval($model->id);
                $model->setStatus(BidStatus::STATUS_FILLED);
                return $this->afterChange($model);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'uploadForm' => $uploadForm,
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
            BidHistory::createUpdated($model->id, $model, \Yii::$app->user->id);
            $model->flag_export = false;
            $model->save(false);
            return $this->afterChange($model);
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
            BidHistory::createUpdated($model->id, $model, \Yii::$app->user->id);
            $model->flag_export = false;
            $model->save(false);
            return $this->redirect(['index']);
        }

        return $this->render('update-status', [
            'model' => $model,
        ]);
    }

    public function actionView($id, $returnUrl = null)
    {
        $this->checkAccess('viewBid', ['bidId' => $id]);

        $model = Bid::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Page not found');
        }

        $bidJobProvider = new ActiveDataProvider([
            'query' => BidJob::find()->where(['bid_id' => $id])->orderBy('updated_at'),
            'pagination' => false
        ]);

        $bidJob1cProvider = new ActiveDataProvider([
            'query' => BidJob1c::find()->where(['bid_id' => $id]),
            'pagination' => false
        ]);

        $replacementPartProvider = new ActiveDataProvider([
            'query' => ReplacementPart::find()->where(['bid_id' => $id])->orderBy('num_order'),
            'pagination' => false
        ]);

        $clientPropositionProvider = new ActiveDataProvider([
            'query' => ClientProposition::find()->where(['bid_id' => $id])->orderBy('num_order'),
            'pagination' => false
        ]);

        $spareProvider = new ActiveDataProvider([
            'query' => Spare::find()->where(['bid_id' => $id])->orderBy('updated_at'),
            'pagination' => false
        ]);

        if (!$model->isViewed(\Yii::$app->user->identity)) {
            $statusService = new ReadStatusService($model->id, \Yii::$app->user->identity);
            $statusService->setStatus();
        }

        $attributes = ViewHelper::getAttributesView($model, \Yii::$app->user);
        $section1 = ViewHelper::getViewSection($model, \Yii::$app->user, 'section1');
        $section2 = ViewHelper::getViewSection($model, \Yii::$app->user, 'section2', false);
        $section3 = ViewHelper::getViewSection($model, \Yii::$app->user, 'section3', false);
        $section4 = ViewHelper::getViewSection($model, \Yii::$app->user, 'section4', false);
        $section5 = ViewHelper::getViewSection($model, \Yii::$app->user, 'section5', false);

        return $this->render('view', [
            'returnUrl' => $returnUrl,
            'model' => $model,
            'bidJobProvider' => $bidJobProvider,
            'bidJob1cProvider' => $bidJob1cProvider,
            'spareProvider' => $spareProvider,
            'replacementPartProvider' => $replacementPartProvider,
            'clientPropositionProvider' => $clientPropositionProvider,
            'attributes' => $attributes,
            'section1' => $section1,
            'section2' => $section2,
            'section3' => $section3,
            'section4' => $section4,
            'section5' => $section5
        ]);
    }

    public function actionDownload($id)
    {
        $this->checkAccess('viewBid', ['bidId' => $id]);

        $model = new SendActForm(['bidId' => $id, 'user' => \Yii::$app->user]);
        $model->act->generate();


        return $this->render('download', compact('model'));
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

    public function actionSetStatusDone($bidId)
    {
        $this->checkAccess('setBidDone', ['bidId' => $bidId]);

        $service = new DoneStatusService($bidId, \Yii::$app->user->identity);
        $service->setStatus();

        return $this->redirect(['send-act/index', 'bidId' => $bidId, 'forced' => true]);
    }

    public function actionUpdateDecisionManager($bidId)
    {
        $this->checkAccess('updateDecisionManager', ['bidId' => $bidId]);

        $model = Bid::findOne($bidId);

        if ($model->load(Yii::$app->request->post())) {
            BidHistory::createUpdated($model->id, $model, \Yii::$app->user->id);
            if (!$model->save()) {
                \Yii::error($model->getErrors());
                throw new \DomainException('Fail to save bid');
            }
        }
        return $this->redirect(['view', 'id' => $bidId]);
    }

    public function actionUpdateDecisionMaster($bidId)
    {
        $this->checkAccess('updateDecisionMaster', ['bidId' => $bidId]);

        $model = Bid::findOne($bidId);

        if ($model->load(Yii::$app->request->post())) {
            BidHistory::createUpdated($model->id, $model, \Yii::$app->user->id);
            if (!$model->save()) {
                \Yii::error($model->getErrors());
                throw new \DomainException('Fail to save bid');
            }
        }
        return $this->redirect(['view', 'id' => $bidId]);
    }

    private function afterChange(Bid $model)
    {
        if (\Yii::$app->request->post('save')) {
            return $this->redirect(['index']);
        } elseif (\Yii::$app->request->post('send')) {
            return $this->redirect(['send-act/index', 'bidId' => $model->id]);
        } elseif (\Yii::$app->request->post('job')) {
            return $this->redirect(['bid-job/index', 'bidId' => $model->id]);
        } elseif (\Yii::$app->request->post('spare')) {
            return $this->redirect(['spare/index', 'bidId' => $model->id]);
        } else {
            throw new \DomainException('Unknown action after bid change');
        }
    }

}