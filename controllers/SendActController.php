<?php


namespace app\controllers;


use app\models\form\SendActForm;
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
        $this->checkAccess('viewBid');
        $model = new SendActForm(['bidId' => $bidId]);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->send()) {
                \Yii::$app->session->setFlash('success', 'Письмо успешно отправлено');
            } else {
                \Yii::$app->session->setFlash('error', 'Ошибка при отправке письма');
            }
            return $this->redirect(['bid/view', 'id' => $bidId]);
        }

        return $this->render('index', ['model' => $model]);
    }

}