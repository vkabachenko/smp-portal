<?php


namespace app\controllers;

use app\models\form\SendActForm;
use app\models\form\UploadExcelTemplateForm;
use app\services\status\SentStatusService;
use yii\filters\AccessControl;
use yii\web\Controller;

class SendActController extends Controller
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

    public function actionIndex($bidId, $subType, $forced = false)
    {
        if (!$forced) {
            $this->checkAccess('sendAct', ['bidId' => $bidId]);
        }

        $model = new SendActForm(['bidId' => $bidId, 'subType' => $subType, 'user' => \Yii::$app->user]);
        $uploadForm = new UploadExcelTemplateForm();

        if (!\Yii::$app->request->isPost) {
            $model->act->generate();
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->send($uploadForm)) {
                \Yii::$app->session->setFlash('success', 'Заявка успешно отправлена');
                if (!$forced) {
                    $statusService = new SentStatusService($bidId, \Yii::$app->user->identity);
                    $statusService->setStatus();
                }
            } else {
                \Yii::$app->session->setFlash('error', 'Ошибка при отправке заявки. Проверьте наличие шаблона акта');
            }
            return $this->redirect(['bid/view', 'id' => $bidId]);
        }

        return $this->render('index', [
            'model' => $model,
            'uploadForm' => $uploadForm
            ]);
    }

    public function actionGenerate($bidId)
    {
        $this->checkAccess('sendAct', ['bidId' => $bidId]);
        $model = new SendActForm(['bidId' => $bidId]);

        if ($model->act->bid->agency->isActTemplate()) {
            $model->act->generate();
        } else {
            \Yii::$app->session->setFlash('error', 'Отсутствует шаблон акта, генерация невозможна');
        }

        $this->redirect(['index', 'bidId' => $bidId]);
    }

}