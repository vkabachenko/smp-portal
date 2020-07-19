<?php


namespace app\controllers;


use app\models\Client;
use app\models\ClientPhone;
use app\models\Master;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;

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
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->checkAccess('updateClient');
        $query = Client::find()->orderBy('name');

        /* @var $master Master */
        $master = Master::findByUserId(\Yii::$app->user->id);

        if ($master) {
            $query->where(['workshop_id' => $master->workshop_id]);
        }

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
        $this->checkAccess('updateClient');
        $model = new Client();

        /* @var $master Master */
        $master = Master::findByUserId(\Yii::$app->user->id);

        if ($master) {
            $model->workshop_id = $master->workshop_id;
        }

        return $this->render('create', compact('model'));
    }

    public function actionUpdate($id)
    {
        $this->checkAccess('updateClient', ['clientId' => $id]);
        $model = Client::findOne($id);

        return $this->render('update', compact('model'));
    }

    public function actionUpdateModal($id)
    {
        $this->checkAccess('updateClient', $id ? ['clientId' => $id] : []);

        if ($id) {
            $client = Client::findOne($id);
        } else {
            $workshopId = \Yii::$app->user->identity->master ? \Yii::$app->user->identity->master->workshop_id : null;
            $client = new Client(['workshop_id' => $workshopId]);
        }

        return $this->renderPartial('_form', compact('client'));
    }

    public function actionDelete($id)
    {
        $this->checkAccess('updateClient', ['clientId' => $id]);
        Client::findOne($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSave()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = \Yii::$app->request->post('id');
        $this->checkAccess('updateClient', $id ? ['clientId' => $id] : []);

        if ($id) {
            $model = Client::findOne($id);
        } else {
            $model = new Client();
        }
        $model->flag_export = false;

        $model->load(\Yii::$app->request->post());

        /* @var $master Master */
        $master = Master::findByUserId(\Yii::$app->user->id);

        if ($master) {
            $model->workshop_id = $master->workshop_id;
        }

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

        return ['id' => $model->id, 'name' => $model->name];
    }

    public function actionAutoComplete()
    {
        $this->checkAccess('updateClient');
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = \Yii::$app->request->post();

        return ['clients' => Client::getClientsByTerm($data['workshopId'], $data['term'])];
    }

}