<?php


namespace app\controllers;

use app\models\form\InviteMasterForm;
use app\models\Master;
use app\models\MasterSignup;
use app\models\User;
use app\models\Workshop;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class WorkshopMasterController extends Controller
{
    use AccessTrait;

    /* @var Workshop */
    public $workshop;

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
                            return \Yii::$app->user->can('manageWorkshops');
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $workshopId = \Yii::$app->request->get('workshopId');
        if (!$workshopId) {
            /* @var $master \app\models\Master */
            $master = Master::findByUserId(\Yii::$app->user->id);
            if (is_null($master)) {
                throw new \DomainException('Workshop not found');
            }
            $this->workshop = $master->workshop;
        } else {
            $this->workshop = Workshop::findOne($workshopId);
        }

        return parent::beforeAction($action);
    }


    public function actionMasters()
    {
        $workshop = $this->workshop;
        $mastersDataProvider = new ActiveDataProvider([
            'query' => $workshop->getMasters()->where(['main' => false]),
        ]);

        return $this->render('index', compact('workshop', 'mastersDataProvider'));
    }

    public function actionAllMasters()
    {
        $workshop = $this->workshop;
        $mastersDataProvider = new ActiveDataProvider([
            'query' => $workshop->getMasters(),
        ]);

        return $this->render('all-masters', compact('workshop', 'mastersDataProvider'));
    }

    public function actionInvite()
    {
        $model = new InviteMasterForm();

        if ($model->load(\Yii::$app->request->post()) && $model->signup($this->workshop)) {
            // send mail
            \Yii::$app->session->setFlash('success', 'Направлено письмо с приглашением');
            return $this->redirect(['masters']);
        }
        return $this->render('invite', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $master = Master::findOne($id);
        $this->checkAccess('manageMasters', ['workshopId' => $master->workshop_id]);
        $user = User::findOne($master->user_id);

        if ($master->load(\Yii::$app->request->post())
            && $master->validate()
            && $user->load(\Yii::$app->request->post())
            && $user->validate()
            && $master->saveMasterUser($user))
        {
            return \Yii::$app->user->can('admin')
                ? $this->redirect(['all-masters', 'workshopId' => $master->workshop_id])
                : $this->redirect(['masters']);
        }
        return $this->render('update', compact('master', 'user'));
    }

    public function actionDelete($id)
    {
        $master = Master::findOne($id);
        $this->checkAccess('manageMasters', ['workshopId' => $master->workshop_id]);
        $master->delete();

        return \Yii::$app->user->can('admin')
            ? $this->redirect(['all-masters', 'workshopId' => $master->workshop_id])
            : $this->redirect(['masters']);
    }
}