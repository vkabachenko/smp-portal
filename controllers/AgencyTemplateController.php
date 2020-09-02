<?php

namespace app\controllers;


use app\models\Agency;
use app\models\form\UploadExcelTemplateForm;
use app\models\TemplateModel;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;

class AgencyTemplateController extends Controller
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

    public function actionIndex($agencyId)
    {
        $agency = Agency::findOne($agencyId);
        return $this->render('index', compact('agency'));
    }

    public function actionManage($agencyId, $type, $sub_type = null)
    {
        $template = TemplateModel::find()
                ->where(['agency_id' => $agencyId, 'type' => $type, 'sub_type' => $sub_type])
                ->one();
        $template = $template ?: new TemplateModel(['agency_id' => $agencyId, 'type' => $type, 'sub_type' => $sub_type]);
        $uploadForm = new UploadExcelTemplateForm();

        if (\Yii::$app->request->isPost) {
            $uploadForm->file = UploadedFile::getInstance($uploadForm, 'file');
            if ($uploadForm->file) {
                $template->file_name = $template->getFilename();
                $uploadForm->file->saveAs($template->getTemplatePath());
            }
            $template->load(\Yii::$app->request->post());
            if ($template->save()) {
                \Yii::$app->session->setFlash('success', 'Шаблон успешно сохранен');
            } else {
                \Yii::error($template->getErrors());
                \Yii::$app->session->setFlash('error', 'Ошибка при сохранении шаблона');
            }
            return $this->redirect(['index', 'agencyId' => $agencyId]);
        }

        return $this->render('manage', compact('template', 'uploadForm'));
    }

    public function actionDelete($id)
    {
        $template = TemplateModel::findOne($id);
        $agencyId = $template->agency_id;
        $template->delete();
        \Yii::$app->session->setFlash('success', 'Шаблон успешно удален');

        return $this->redirect(['index', 'agencyId' => $agencyId]);
    }

}