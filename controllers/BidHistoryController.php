<?php


namespace app\controllers;


use app\models\form\MultipleUploadForm;
use app\models\Image;
use app\models\search\BidHistorySearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\BidHistory;
use yii\web\UploadedFile;

class BidHistoryController  extends Controller
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

    public function actionIndex($bidId)
    {
        $this->checkAccess('viewBid');

        $searchModel = new BidHistorySearch();
        $dataProvider = $searchModel->search($bidId);

        return $this->render('index', [
            'bidId' => $bidId,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($bidId)
    {
        $this->checkAccess('createBid');

        $model = new BidHistory(['bid_id' => $bidId, 'user_id' => \Yii::$app->user->id]);

        $uploadForm = new MultipleUploadForm();

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            $uploadForm->files = UploadedFile::getInstances($uploadForm, 'files');
            $uploadForm->upload($model);

            return $this->redirect(['index', 'bidId' => $bidId]);
        }

        return $this->render('create', [
            'model' => $model,
            'uploadForm' => $uploadForm
        ]);
    }
}