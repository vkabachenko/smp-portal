<?php


namespace app\controllers;


use app\models\form\SendActForm;
use app\models\form\UploadExcelTemplateForm;
use yii\filters\AccessControl;
use yii\web\Controller;

class SendActController extends Controller
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
        $this->checkAccess('viewBid', ['bidId' => $bidId]);
        $model = new SendActForm(['bidId' => $bidId]);
        $uploadForm = new UploadExcelTemplateForm();

        if (!\Yii::$app->request->isPost && !$model->act->isGenerated()) {
            $model->act->generate();
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->send($uploadForm)) {
                \Yii::$app->session->setFlash('success', 'Письмо успешно отправлено');
            } else {
                \Yii::$app->session->setFlash('error', 'Ошибка при отправке письма');
            }
            return $this->redirect(['bid/view', 'id' => $bidId]);
        }

        return $this->render('index', [
            'model' => $model,
            'uploadForm' => $uploadForm
            ]);
    }

    public function actionGenerate($bidId)
    {
        $this->checkAccess('viewBid', ['bidId' => $bidId]);
        $model = new SendActForm(['bidId' => $bidId]);

        if ($model->act->bid->manufacturer->isActTemplate()) {
            $model->act->generate();
        } else {
            \Yii::$app->session->setFlash('error', 'Отсутствует шаблон акта, генерация невозможна');
        }

        $this->redirect(['index', 'bidId' => $bidId]);
    }

}