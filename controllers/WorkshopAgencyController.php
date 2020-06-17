<?php


namespace app\controllers;


use app\models\Agency;
use app\models\AgencyWorkshop;
use app\models\form\UploadImageForm;
use app\models\Manager;
use app\models\Master;
use app\models\OfficialDocs;
use app\models\Workshop;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

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
        $workshop->addIndependentAgencies();
        $agencyDataProvider = new ActiveDataProvider([
            'query' => $workshop->getAllAgencies(),
        ]);

        return $this->render(\Yii::$app->user->can('admin') ? 'index-admin' : 'index',
            compact('workshop','agencyDataProvider'));
    }

    public function actionToggleActive($workshopId, $agencyId, $returnUrl)
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
        return $this->redirect($returnUrl);
    }

    public function actionUpdate($workshopId, $agencyId)
    {
        /* @var $agencyWorkshop \app\models\AgencyWorkshop */
        $agencyWorkshop = AgencyWorkshop::find()->where(['agency_id' => $agencyId, 'workshop_id' => $workshopId])->one();
        if (is_null($agencyWorkshop)) {
            throw new \DomainException('AgencyWorkshop not found');
        }

        $uploadImageForm = new UploadImageForm();

        if ($agencyWorkshop->load(\Yii::$app->request->post()) && $agencyWorkshop->save()) {
            if ($uploadImageForm->load(\Yii::$app->request->post())) {
                $officialDoc = AgencyWorkshop::getOfficialDoc($agencyWorkshop->agency, $this->workshop);
                if (is_null($officialDoc)) {
                    $officialDoc = new OfficialDocs(['model' => 'AgencyWorkshop', 'model_id' => $agencyWorkshop->id]);
                }

                $uploadImageForm->file = UploadedFile::getInstance($uploadImageForm, 'file');
                $officialDoc->file_name = $uploadImageForm->file->name;
                $officialDoc->src_name = \Yii::$app->security->generateRandomString() . '.' . $uploadImageForm->file->extension;
                if ($officialDoc->save()) {
                    $uploadImageForm->file->saveAs($officialDoc->getPath());
                } else {
                    \Yii::error($officialDoc->getErrors());
                    throw new \DomainException('Fail to save image');
                }
                return $this->redirect(['agencies', 'workshopId' => $workshopId]);
            }
        }

        return $this->render('update', compact('agencyWorkshop', 'uploadImageForm'));
    }

}