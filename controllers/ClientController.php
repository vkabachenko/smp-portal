<?php


namespace app\controllers;


use app\models\Client;
use app\models\ClientPhone;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use function foo\func;

class ClientController extends Controller
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

    public function actionSave()
    {
        $this->checkAccess('updateClient');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = \Yii::$app->request->post('id');

        if ($id) {
            $model = Client::findOne($id);
        } else {
            $model = new Client();
        }

        $model->load(\Yii::$app->request->post());

        if (!$model->save()) {
            throw new \DomainException(Json::encode($model->errors));
        }

        ClientPhone::deleteAll(['client_id' => $model->id]);

        $phones = \Yii::$app->request->post('clientPhone');
        $phones = array_filter($phones, function($el) {return !empty($el);});
        $phones = array_map(function($phone) use ($model) {return [$model->id, $phone];}, $phones);

        \Yii::$app->db
            ->createCommand()
            ->batchInsert('client_phone', ['client_id', 'phone'], $phones)
            ->execute();

        return ['id' => $model->id];
    }

}