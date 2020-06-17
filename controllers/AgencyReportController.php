<?php

namespace app\controllers;


use app\models\Agency;
use app\models\form\ReportForm;
use app\models\Report;
use app\templates\excel\report\ExcelReport;
use yii\data\ActiveDataProvider;
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
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->can('updateAgency');
                        }
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Report::find()
                ->where(['agency_id' => \Yii::$app->user->identity->manager->agency_id])
                ->andWhere(['is_transferred' => true])
                ->orderBy('workshop_id, report_date DESC'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}