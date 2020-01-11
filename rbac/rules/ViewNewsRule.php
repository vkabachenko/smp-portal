<?php


namespace app\rbac\rules;

use app\models\News;
use app\models\User;
use yii\rbac\Rule;

class ViewNewsRule extends Rule
{
    public $name = 'isViewNews';

    public function execute($user, $item, $params)
    {
        /* @var $userModel \app\models\User */
        $userModel = User::findOne($user);

        if (isset($params['newsId'])) {
            $article = News::findOne($params['newsId']);

            if (is_null($article)) {
                return false;
            }

            if ($userModel->master) {
                return $article->target == 'all' || $article->target == 'workshops';
            } else {
                if ($userModel->manager) {
                    return $article->target == 'all' || $article->target == 'agencies';
                } else {
                    return false;
                }
            }
        } else {
            return $userModel->master || $userModel->manager;
        }
    }
}