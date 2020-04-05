<?php

namespace app\controllers;

use app\models\BidAttribute;
use app\models\form\WorkshopRulesForm;
use Yii;
use app\models\Workshop;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * WorkshopController implements the CRUD actions for Workshop model.
 */
class WorkshopController extends Controller
{
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

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Workshop::find()->orderBy('name'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate()
    {
        $model = new Workshop();
        $rules = new WorkshopRulesForm();

        if ($model->load(Yii::$app->request->post()) && $rules->load(Yii::$app->request->post()) ) {
            $model->rules = $rules->attributes;
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'rules' => $rules
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $rules = new WorkshopRulesForm($model->rules);

        if ($model->load(Yii::$app->request->post()) && $rules->load(Yii::$app->request->post()) ) {
            $model->rules = $rules->attributes;
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'rules' => $rules
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionBidAttributes($workshopId)
    {
        $workshop = $this->findModel($workshopId);
        $ownAttributes = $workshop->getBidAttributes('bid_attributes');
        $availableAttributes = $workshop->getAvailableAttributes();

        return $this->render('bid-attributes', compact('workshop', 'ownAttributes', 'availableAttributes'));
    }

    public function actionBidAttributeMove($workshopId)
    {
        $workshop = $this->findModel($workshopId);
        $ownAttributes = $workshop->getBidAttributes('bid_attributes');

        $attribute = [\Yii::$app->request->post('attribute')];
        $action = \Yii::$app->request->post('action');

        switch ($action) {
            case 'add':
                $ownAttributes = array_merge($ownAttributes, $attribute);
                break;
            case 'remove':
                $ownAttributes = array_diff($ownAttributes, $attribute);
                break;
            default:
                throw new \DomainException('Unknown action');
        }
        $workshop->bid_attributes = $ownAttributes;
        if (!$workshop->save()) {
            \Yii::error($workshop->getErrors());
            throw new \DomainException('Fail to save workshop');
        }

        return $this->redirect(['bid-attributes', 'workshopId' => $workshop->id]);
    }

    public function actionBidAttributesSections($workshopId)
    {
        $workshop = $this->findModel($workshopId);
        $bidSection = $workshop->getSectionsAttributes();

        return $this->render('bid-attributes-sections', compact('workshop', 'bidSection'));
    }

    public function actionSaveAttributesSections($workshopId)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $workshop = $this->findModel($workshopId);

        $section1 = \Yii::$app->request->post('section1');
        $section2 = \Yii::$app->request->post('section2');
        $section3 = \Yii::$app->request->post('section3');

        $workshop->bid_attributes_section1 = $section1;
        $workshop->bid_attributes_section2 = $section2;
        $workshop->bid_attributes_section3 = $section3;

        if ($workshop->save()) {
            $result = 'ok';
        } else {
            $result = 'fail';
            \Yii::error($workshop->getErrors());
        }

        return ['result' => $result];
    }

    protected function findModel($id)
    {
        if (($model = Workshop::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
