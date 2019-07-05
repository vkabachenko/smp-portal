<?php


namespace app\controllers;


use app\models\form\MultipleUploadForm;
use app\models\BidHistory;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;

class BidImageController  extends Controller
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

    public function actionCreate($bidId)
    {
        $this->checkAccess('createBid');

        $attributes = ['bid_id' => $bidId, 'user_id' => \Yii::$app->user->id];

        $uploadForm = new MultipleUploadForm();

        if (\Yii::$app->request->isPost) {
            $uploadForm->files = UploadedFile::getInstances($uploadForm, 'files');
            $uploadForm->upload($attributes);
            BidHistory::createRecord(['bid_id' => $bidId, 'user_id' => \Yii::$app->user->id, 'action' => 'Новые фото']);

            return $this->redirect(['bid/view', 'id' => $bidId]);
        }

        return $this->render('create', [
            'uploadForm' => $uploadForm
        ]);
    }


}