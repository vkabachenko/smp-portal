<?php

namespace app\controllers;

use app\models\Agency;
use app\models\BidAttribute;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;


class AgencyController extends Controller
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
                        'roles' => ['admin'],
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
            'query' => Agency::find()->orderBy('name'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save() ) {

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionBidAttributes($agencyId)
    {
        $agency = $this->findModel($agencyId);
        $ownAttributes = $agency->getBidAttributes('bid_attributes');
        $availableAttributes = $agency->getAvailableAttributes();

        return $this->render('bid-attributes', compact('agency', 'ownAttributes', 'availableAttributes'));
    }

    public function actionBidAttributesSections($agencyId)
    {
        $agency = $this->findModel($agencyId);
        $bidSection = $agency->getSectionsAttributes();

        return $this->render('bid-attributes-sections', compact('agency', 'bidSection'));
    }

    public function actionSaveAttributesSections($agencyId)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $agency = $this->findModel($agencyId);

        $section1 = \Yii::$app->request->post('section1');
        $section2 = \Yii::$app->request->post('section2');
        $section3 = \Yii::$app->request->post('section3');
        $section4 = \Yii::$app->request->post('section4');
        $section5 = \Yii::$app->request->post('section5');

        $agency->bid_attributes_section1 = $section1;
        $agency->bid_attributes_section2 = $section2;
        $agency->bid_attributes_section3 = $section3;
        $agency->bid_attributes_section4 = $section4;
        $agency->bid_attributes_section5 = $section5;

        if ($agency->save()) {
            $result = 'ok';
        } else {
            $result = 'fail';
            \Yii::error($agency->getErrors());
        }

        return ['result' => $result];
    }

    public function actionBidAttributeMove($agencyId)
    {
        $agency = $this->findModel($agencyId);
        $ownAttributes = $agency->getBidAttributes('bid_attributes');

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
        $agency->bid_attributes = $ownAttributes;
        if (!$agency->save()) {
            \Yii::error($agency->getErrors());
            throw new \DomainException('Fail to save agency');
        }

        return $this->redirect(['bid-attributes', 'agencyId' => $agency->id]);
    }

    protected function findModel($id)
    {
        if (($model = Agency::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}
