<?php

namespace app\controllers;

use app\models\User;
use Yii;
use app\models\Master;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterCrudController implements the CRUD actions for Workshop model.
 */
class MasterCrudController extends Controller
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
                            return \Yii::$app->user->can('manageMasters');
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Master::find()
                ->joinWith(['user', 'workshop'])
                ->orderBy('workshop.name, user.name'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate()
    {
        $master = new Master();
        $user = new User();

        if ($master->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
            $user->save();
            $master->user_id = $user->id;
            $master->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'master' => $master,
            'user' => $user
        ]);
    }


    public function actionUpdate($id)
    {
        $master = $this->findModel($id);
        $user = User::findOne($master->user_id);

        if ($master->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
            $user->save();
            $master->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'master' => $master,
            'user' => $user
        ]);
    }



    protected function findModel($id)
    {
        if (($model = Master::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
