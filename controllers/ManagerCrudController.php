<?php

namespace app\controllers;

use app\models\User;
use Yii;
use app\models\Manager;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ManagerCrudController implements the CRUD actions for Workshop model.
 */
class ManagerCrudController extends Controller
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
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Manager::find()
                ->joinWith(['user', 'manufacturer'])
                ->orderBy('manufacturer.name, user.name'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate()
    {
        $manager = new Manager();
        $user = new User();

        if ($manager->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
            $user->save();
            $manager->user_id = $user->id;
            $manager->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'manager' => $manager,
            'user' => $user
        ]);
    }


    public function actionUpdate($id)
    {
        $manager = $this->findModel($id);
        $user = User::findOne($manager->user_id);

        if ($manager->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
            $user->save();
            $manager->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'manager' => $manager,
            'user' => $user
        ]);
    }



    protected function findModel($id)
    {
        if (($model = Manager::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
