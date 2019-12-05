<?php


namespace app\controllers;

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
        $workshopId = \Yii::$app->request->get('agencyId');
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

    public function actionInvite()
    {
        $token = \Yii::$app->security->generateRandomString(16);
        $masterSignupModel = new MasterSignup([
            'workshop_id' => $this->workshop->id,
            'token' => $token
        ]);

        if (!$masterSignupModel->save()) {
            \Yii::error($masterSignupModel->getErrors());
            \Yii::$app->session->setFlash('error', 'Fail saving master signup model');
        } else {
            \Yii::$app->session->setFlash('success',
                'Передайте в e-mail или мессенджере ссылку на приглашение мастера: ' .
                \Yii::$app->urlManager->createAbsoluteUrl(['master-signup/index', 'token' => $token])
                );
        }

        return $this->redirect(['masters']);
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
            return $this->redirect(['masters']);
        }
        return $this->render('update', compact('master', 'user'));
    }

    public function actionDelete($id)
    {
        $master = Master::findOne($id);
        $this->checkAccess('manageMasters', ['workshopId' => $master->workshop_id]);
        $master->delete();

        return $this->redirect(['masters']);
    }
}