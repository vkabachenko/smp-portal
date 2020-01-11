<?php

namespace app\controllers;

use app\models\News;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;


class NewsShowController extends Controller
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
            ]
        ];
    }

    public function actionArticle($id)
    {
        $this->checkAccess('viewNews', ['newsId' => $id]);
        $article = News::findOne($id);

        return $this->render('article', compact('article'));
    }

    public function actionAllNews()
    {
        $this->checkAccess('viewNews');

        Url::remember();

        $userRole = \Yii::$app->user->identity->role;
        switch ($userRole) {
            case 'master':
                $condition = ['target' => 'workshops'];
                break;
            case 'manager':
                $condition = ['target' => 'agencies'];
                break;
        }
        $query = News::find()
            ->published()
            ->andWhere(['or', ['target' => 'all'], $condition])
            ->orderBy('updated_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('all-news', compact('dataProvider'));
    }
}
