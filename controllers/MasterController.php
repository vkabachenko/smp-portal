<?php


namespace app\controllers;

use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\User;
use app\models\Master;

class MasterController extends Controller
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
                        'roles' => ['master'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        Url::remember();
        return $this->render('index');
    }

    public function actionProfile()
    {
        /* @var $user User */
        $user = \Yii::$app->user->identity;
        $master = Master::findByUserId(\Yii::$app->user->id);
        $workshop = $master->workshop;

        if (\Yii::$app->request->isPost) {
            $userCheck = intval($user->load(\Yii::$app->request->post()) && $user->validate());
            $masterCheck = intval($master->load(\Yii::$app->request->post()) && $master->validate());
            $workshopCheck = \Yii::$app->user->can('manageWorkshops')
                ? intval($workshop->load(\Yii::$app->request->post()) && $workshop->validate())
                : 1;
            if ($userCheck * $masterCheck * $workshopCheck) {
                $transaction = \Yii::$app->db->beginTransaction();
                if (!$user->save()) {
                    \Yii::$app->session->setFlash('error', 'User save error');
                    \Yii::error($user->getErrors());
                    $transaction->rollBack();
                    return $this->redirect(['index']);
                }
                if (!$master->save()) {
                    \Yii::$app->session->setFlash('error', 'Master save error');
                    \Yii::error($master->getErrors());
                    $transaction->rollBack();
                    return $this->redirect(['index']);
                }
                if (\Yii::$app->user->can('manageWorkshops')) {
                    if (!$workshop->save()) {
                        \Yii::$app->session->setFlash('error', 'Workshop save error');
                        \Yii::error($workshop->getErrors());
                        $transaction->rollBack();
                        return $this->redirect(['index']);
                    }
                }
                \Yii::$app->session->setFlash('success', 'Профиль успешно обновлен');
                $transaction->commit();
                return $this->redirect(['index']);
            }
        }

        return $this->render('profile', compact('user', 'master', 'workshop'));
    }

}