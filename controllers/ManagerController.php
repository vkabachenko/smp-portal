<?php


namespace app\controllers;

use app\models\Manager;
use app\models\News;
use app\models\User;
use app\services\news\NewsShowService;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

class ManagerController extends Controller
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
                        'roles' => ['manager'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        Url::remember('', 'main-page');
        $news = News::getPublishedNews('agencies', \Yii::$app->params['limitNews']);
        $newsShowServices = NewsShowService::getAll($news, \Yii::$app->user->identity);
        return $this->render('index', compact('news', 'newsShowServices'));
    }

    public function actionProfile()
    {
        /* @var $user User */
        $user = \Yii::$app->user->identity;
        $manager = Manager::findByUserId(\Yii::$app->user->id);
        $agency = $manager->agency;

        if (\Yii::$app->request->isPost) {
            $userCheck = intval($user->load(\Yii::$app->request->post()) && $user->validate());
            $managerCheck = intval($manager->load(\Yii::$app->request->post()) && $manager->validate());
            $agencyCheck = \Yii::$app->user->can('updateAgency')
                ? intval($agency->load(\Yii::$app->request->post()) && $agency->validate())
                : 1;
            if ($userCheck * $managerCheck * $agencyCheck) {
                $transaction = \Yii::$app->db->beginTransaction();
                if (!$user->save()) {
                    \Yii::$app->session->setFlash('error', 'User save error');
                    \Yii::error($user->getErrors());
                    $transaction->rollBack();
                    return $this->redirect(['index']);
                }
                if (!$manager->save()) {
                    \Yii::$app->session->setFlash('error', 'Manager save error');
                    \Yii::error($manager->getErrors());
                    $transaction->rollBack();
                    return $this->redirect(['index']);
                }
                if (\Yii::$app->user->can('updateAgency')) {
                    if (!$agency->save()) {
                        \Yii::$app->session->setFlash('error', 'Agency save error');
                        \Yii::error($agency->getErrors());
                        $transaction->rollBack();
                        return $this->redirect(['index']);
                    }
                }
                \Yii::$app->session->setFlash('success', 'Профиль успешно обновлен');
                $transaction->commit();
                return $this->redirect(['index']);
            }
        }

        return $this->render('profile', compact('user', 'manager', 'agency'));
    }


}