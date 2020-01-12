<?php


namespace app\services\news;


use app\models\NewsLike;

class NewsShowService
{
    public $countUp;
    public $countDown;

    public function countLikes($newsId, $userId)
    {
        $this->countUp = NewsLike::countLikes($newsId, $userId, 'like');
        $this->countDown = NewsLike::countLikes($newsId, $userId, 'dislike');
    }

    public function addOrDeleteStatus($newsId, $userId, $status)
    {
        $prevLike = NewsLike::find()->where(['user_id' => $userId, 'news_id' => $newsId])->one();

        if ($prevLike) {
            $prevLike->delete();
        } else {
            $status = \Yii::$app->request->post('status');
            $model = new NewsLike(['user_id' => $userId, 'news_id' => $newsId, 'status' => $status]);
            $model->save();
        }
    }

}