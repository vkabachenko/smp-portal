<?php


namespace app\controllers;


use app\models\Agency;
use app\models\form\InviteManagerForm;
use app\models\Manager;
use app\models\ManagerSignup;
use app\models\User;
use app\models\Workshop;
use app\services\mail\InviteManager;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class AgencyManagerController extends Controller
{
    use AccessTrait;

    /* @var Agency */
    public $agency;

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
                            return \Yii::$app->user->can('updateAgency');
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
        $agencyId = \Yii::$app->request->get('agencyId');
        if (!$agencyId) {
            /* @var $manager \app\models\Manager */
            $manager = Manager::findByUserId(\Yii::$app->user->id);
            if (is_null($manager)) {
                throw new \DomainException('Agency not found');
            }
            $this->agency = $manager->agency;
        } else {
            $this->agency = Agency::findOne($agencyId);
        }

        return parent::beforeAction($action);
    }


    public function actionManagers()
    {
        $agency = $this->agency;
        $managersDataProvider = new ActiveDataProvider([
            'query' => $agency->getManagers()->where(['main' => false]),
        ]);

        return $this->render('index', compact('agency', 'managersDataProvider'));
    }

    public function actionAllManagers()
    {
        $agency = $this->agency;
        $managersDataProvider = new ActiveDataProvider([
            'query' => $agency->getManagers(),
        ]);

        return $this->render('all-managers', compact('agency', 'managersDataProvider'));
    }

    public function actionInvite()
    {
        $model = new InviteManagerForm();

        if ($model->load(\Yii::$app->request->post()) && $model->signup($this->agency)) {
            $mailService = new InviteManager($model->manager);
            if ($mailService->send()) {
                \Yii::$app->session->setFlash('success', 'Направлено письмо с приглашением');
            } else {
                \Yii::$app->session->setFlash('error', 'Не удалось отправить письмо на ваш e-mail. Обратитесь к администратору сайта или попробуйте позднее');
            }

            return $this->redirect(['managers']);
        }
        return $this->render('invite', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $manager = Manager::findOne($id);
        $this->checkAccess('manageManagers', ['agencyId' => $manager->agency_id]);
        $user = User::findOne($manager->user_id);

        if ($manager->load(\Yii::$app->request->post())
            && $manager->validate()
            && $user->load(\Yii::$app->request->post())
            && $user->validate()
            && $manager->saveManagerUser($user))
        {
            return \Yii::$app->user->can('admin')
                ? $this->redirect(['all-managers', 'agencyId' => $manager->agency_id])
                : $this->redirect(['managers']);
        }
        return $this->render('update', compact('manager', 'user'));
    }

    public function actionDelete($id)
    {
        $manager = Manager::findOne($id);
        $this->checkAccess('manageManagers', ['agencyId' => $manager->agency_id]);
        $manager->user->delete();

        return \Yii::$app->user->can('admin')
            ? $this->redirect(['all-managers', 'agencyId' => $manager->agency_id])
            : $this->redirect(['managers']);
    }

}