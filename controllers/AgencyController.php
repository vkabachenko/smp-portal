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


    public function actionCreate()
    {
        $model = new Agency();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
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
        $ownAttributes = $agency->getBidAttributes();
        $availableAttributes = array_diff(
            BidAttribute::getAvailableAttributes('is_disabled_agencies'), $ownAttributes);

        return $this->render('bid-attributes', compact('agency', 'ownAttributes', 'availableAttributes'));
    }

    public function actionBidAttributeMove($agencyId)
    {
        $agency = $this->findModel($agencyId);
        $ownAttributes = $agency->getBidAttributes();

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
