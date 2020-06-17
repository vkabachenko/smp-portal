<?php


namespace app\controllers;


use app\models\form\EmailTemplateForm;
use app\models\TemplateModel;
use yii\filters\AccessControl;
use yii\web\Controller;

class EmailTemplateController extends Controller
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

    public function actionUpdate()
    {
        $model = new EmailTemplateForm();
        $model->fillFieldsFromDb();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
                if ($model->saveFieldsToDb()) {
                    \Yii::$app->session->setFlash('success', 'Успешно обновлено');
                } else {
                    \Yii::$app->session->setFlash('success', 'Ошибка при обновлении');
                }
                return $this->refresh();
        }

        return $this->render('update', ['model' => $model]);
    }

}