<?php


namespace app\services\news;


use app\models\NewsLike;

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

}