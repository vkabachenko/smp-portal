<?php


namespace app\controllers;


use app\models\Client;
use app\models\ClientPhone;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use function foo\func;

class ClientController extends Controller
{
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
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('updateClient');
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $query = Client::find()->orderBy('name');
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        Url::remember();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Client();

        return $this->render('create', compact('model'));
    }

    public function actionUpdate($id)
    {
        $model = Client::findOne($id);

        return $this->render('update', compact('model'));
    }

    public function actionDelete($id)
    {
        Client::findOne($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSave()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = \Yii::$app->request->post('id');

        if ($id) {
            $model = Client::findOne($id);
        } else {
            $model = new Client();
        }
        $model->flag_export = false;

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