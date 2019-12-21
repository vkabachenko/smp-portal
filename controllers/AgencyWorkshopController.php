<?php


namespace app\controllers;


use app\models\Agency;
use app\models\Manager;
use app\models\Workshop;
use app\services\mail\CreateAgencyWorkshop;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

class AgencyWorkshopController extends Controller
{
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

    public function actionWorkshops()
    {
        $agency = $this->agency;
        $workshopDataProvider = new ActiveDataProvider([
            'query' => $agency->getAllWorkshops(),
        ]);

        $workshopsId = array_map(function(Workshop $workshop) { return $workshop->id; }, $agency->allWorkshops);
        $availableWorkshops = Workshop::find()
            ->select(['name', 'id'])
            ->where(['NOT',['id' => $workshopsId]])
            ->indexBy('id')
            ->column();

        return $this->render('index', compact('agency','workshopDataProvider', 'availableWorkshops'));
    }

    public function actionDelete($id)
    {
        $workshop = Workshop::findOne($id);
        $this->agency->unlink('allWorkshops', $workshop, true);

        return $this->redirect(['workshops', 'agencyId' => $this->agency->id]);
    }

    public function actionNewWorkshop()
    {
        $newWorkshopId = \Yii::$app->request->post('new_workshop');
        if (!$newWorkshopId) {
            throw new \DomainException('New workshop not found');
        }
        $workshop = Workshop::findOne($newWorkshopId);
        $this->agency->link('allWorkshops', $workshop);

        $mailService = new CreateAgencyWorkshop($this->agency, $workshop);
        $mailService->send();

        return $this->redirect(['workshops', 'agencyId' => $this->agency->id]);
    }
}