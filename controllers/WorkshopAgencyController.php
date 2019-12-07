<?php


namespace app\controllers;


use app\models\Agency;
use app\models\AgencyWorkshop;
use app\models\Manager;
use app\models\Master;
use app\models\Workshop;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

class WorkshopAgencyController extends Controller
{
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
            ]
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

    public function actionAgencies()
    {
        $workshop = $this->workshop;
        $agencyDataProvider = new ActiveDataProvider([
            'query' => $workshop->getAllAgencies(),
        ]);
        Url::remember();

        return $this->render(\Yii::$app->user->can('admin') ? 'index-admin' : 'index',
            compact('workshop','agencyDataProvider'));
    }

    public function actionToggleActive($workshopId, $agencyId)
    {
        /* @var $model \app\models\AgencyWorkshop */
        $model = AgencyWorkshop::find()->where(['agency_id' => $agencyId, 'workshop_id' => $workshopId])->one();
        if (is_null($model)) {
            throw new \DomainException('AgencyWorkshop model not found');
        }
        $model->active = !$model->active;
        if (!$model->save()) {
            \Yii::error($model->getErrors());
            throw new \DomainException('Fail AgencyWorkshop model save');
        }
        return $this->redirect(['agencies', 'workshopId' => $workshopId]);
    }
}