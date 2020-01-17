<?php

namespace app\controllers;

use app\models\Agency;
use app\models\JobsCatalog;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class JobsCatalogController  extends Controller
{
    use AccessTrait;

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
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($agencyId)
    {
        $this->checkAccess('manageJobsCatalog', compact('agencyId'));
        $agency = Agency::findOne($agencyId);
        //$dateActual = $dateActual ?: date('Y-m-d', time());
        $query = JobsCatalog::find()
            -> where(['agency_id' => $agencyId])
            -> orderBy('data_actual');
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $this->render('index', [
            'agency' => $agency,
            'dataProvider' => $dataProvider,
        ]);
    }

}