<?php

namespace app\controllers;


use app\models\Agency;
use app\models\form\ReportForm;
use app\templates\excel\report\ExcelReport;
use yii\filters\AccessControl;
use yii\web\Controller;

class AgencyReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex($agencyId)
    {
        $agency = Agency::findOne($agencyId);

        if (empty($agency->report_template)) {
            \Yii::$app->session->setFlash('error', 'Не найден шаблон отчета');
            return $this->redirect(['bid/index']);
        }

        $reportForm = new ReportForm();

        if ($reportForm->load(\Yii::$app->request->post())) {
            $excelReport = new ExcelReport($agencyId, $reportForm);
            $excelReport->generate();
            return $this->render('download', compact('excelReport'));
        }

        return $this->render('index', compact('agency', 'reportForm'));
    }

}