<?php

namespace app\controllers;

use app\models\News;
use app\services\news\NewsShowService;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class NewsShowController extends Controller
{
    /* @var NewsShowService */
    private $newsShowService;

    use AccessTrait;

    public function __construct(
        $id,
        $module,
        NewsShowService $newsShowService,
        $config = []
    ) {
        $this->newsShowService = $newsShowService;
        parent::__construct($id, $module, $config = []);
    }

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
        $this->newsShowService->countLikes($id, \Yii::$app->user->id);

        return $this->render('article', [
            'article' => $article,
            'newsShowService' => $this->newsShowService,
            ]);
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

    public function actionAddLike($id)
    {
        $this->checkAccess('viewNews', ['newsId' => $id]);

        if (!\Yii::$app->request->isPost) {
            throw new ForbiddenHttpException();
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $this->newsShowService->addOrDeleteStatus($id, \Yii::$app->user->id, \Yii::$app->request->post('status'));
        $this->newsShowService->countLikes($id, \Yii::$app->user->id);

        return [
                    'countUp' => $this->newsShowService->countUp,
                    'countDown' => $this->newsShowService->countDown,
                    'isUserUp' => $this->newsShowService->isUserUp,
                    'isUserDown' => $this->newsShowService->isUserDown,
               ];
    }
}
