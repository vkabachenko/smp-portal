<?php

namespace app\controllers;

use app\models\Agency;
use Yii;
use app\models\JobsSection;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JobsSectionController implements the CRUD actions for JobsSection model.
 */
class JobsSectionController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($agencyId)
    {
        $this->checkAccess('manageJobsCatalog', compact('agencyId'));
        $agency = Agency::findOne($agencyId);
        $dataProvider = new ActiveDataProvider([
            'query' => JobsSection::find()->orderBy('name'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'agency' => $agency
        ]);
    }


    public function actionCreate($agencyId)
    {
        $this->checkAccess('manageJobsCatalog', compact('agencyId'));
        $model = new JobsSection(['agency_id' => $agencyId]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'agencyId' => $agencyId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('manageJobsCatalog', ['agencyId' => $model->agency_id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'agencyId' => $model->agency_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = JobsSection::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $agencyId = $model->agency_id;
        $this->checkAccess('manageJobsCatalog', ['agencyId' => $agencyId]);
        $model->delete();

        return $this->redirect(['index', 'agencyId' => $agencyId]);
    }
}
