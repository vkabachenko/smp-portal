<?php


namespace app\controllers;


use app\models\AgencyWorkshop;
use app\models\Bid;
use app\models\BidStatus;
use app\models\Master;
use app\models\Report;
use app\models\Workshop;
use app\templates\excel\report\ExcelReport;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class WorkshopReportController extends Controller
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

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Report::find()
                ->where(['workshop_id' => $this->workshop->id])
                ->orderBy('agency_id, report_date DESC'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSelectAgency()
    {
        $agencies = AgencyWorkshop::find()
            ->joinWith('agency')
            ->select(['agency.name','agency_id'])
            ->where(['workshop_id' => $this->workshop->id])
            ->andWhere(['active' => true])
            ->indexBy('agency_id')
            ->column();

        if (empty($agencies)) {
            \Yii::$app->session->setFlash('error', 'Нет представительств у мастерской');
            return $this->redirect(['index']);
        }

        if (count($agencies) === 1) {
            return $this->redirect(['create', 'agencyId' => array_key_first($agencies)]);
        }

        if ($agencyId = \Yii::$app->request->post('agencyId')) {
            return $this->redirect(['create', 'agencyId' => $agencyId]);
        }

        return $this->render('select-agency', [
            'agencies' => $agencies,
        ]);
    }

    public function actionCreate($agencyId)
    {
        $bids = Bid::find()
            ->select(['equipment', 'bid_number'])
            ->andWhere(['agency_id' => $agencyId])
            ->andWhere(['workshop_id' => $this->workshop->id])
            ->andWhere(['status_id' => BidStatus::getId(BidStatus::STATUS_DONE)])
            ->andWhere(['report_id' => null])
            ->orderBy('created_at')
            ->indexBy('bid_number')
            ->column();

        if (empty($bids)) {
            \Yii::$app->session->setFlash('error', 'Нет выполненных заявок для отчета');
            return $this->redirect(['index']);
        }

        $report = new Report([
            'agency_id' => $agencyId,
            'workshop_id' => $this->workshop->id,
            'selectedBids' => $bids,
            'report_date' => date('Y-m-d')
        ]);

        if (!$report->agency->report_template) {
            \Yii::$app->session->setFlash('error', 'Отсутствует шаблон отчета');
            return $this->redirect(['index']);
        }

        if ($report->load(\Yii::$app->request->post())) {
            if (empty($report->selectedBids)) {
                \Yii::$app->session->setFlash('error', 'Не выбраны заявки');
                return $this->redirect(['index']);
            }
            $excelReport = new ExcelReport($report);
            $excelReport->generate();
            $report->report_filename = $excelReport->getPath();
            if($report->save()) {
                \Yii::$app->session->setFlash('success', 'Отчет успешно сохранен');
            } else {
                \Yii::error($report->getErrors());
                \Yii::$app->session->setFlash('error', 'Не удалось сохранить отчет');
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', compact('report'));
    }

    public function actionUpdate($id)
    {

    }

    public function actionView($id)
    {

    }

    public function actionDelete($id)
    {

    }
}