<?php


namespace app\services\news;


use app\models\News;
use app\models\NewsLike;
use app\models\User;

class NewsShowService
{
    public $countUp;
    public $isUserUp;
    public $countDown;
    public $isUserDown;

    public function countLikes($newsId, $userId)
    {
        $this->countUp = NewsLike::countLikes($newsId, 'like');
        $this->countDown = NewsLike::countLikes($newsId, 'dislike');
        $this->isUserUp = NewsLike::isUserLike($newsId, $userId, 'like');
        $this->isUserDown = NewsLike::isUserLike($newsId, $userId, 'dislike');
    }

    public function addOrDeleteStatus($newsId, $userId, $status)
    {
        $prevLike = NewsLike::find()->where(['user_id' => $userId, 'news_id' => $newsId])->one();

        if ($prevLike) {
            $prevLike->delete();
        } else {
            $model = new NewsLike(['user_id' => $userId, 'news_id' => $newsId, 'status' => $status]);
            $model->save();
        }
    }

    /**
     * @param $news News[]
     * @param $user User
     * @return NewsShowService[]
     */
    public static function getAll($news, $user)
    {
        $services = [];
        foreach ($news as $article) {
            $service = new self();
            $service->countLikes($article->id, $user->id);
            $services[$article->id] = $service;
        }

        return $services;
    }

}