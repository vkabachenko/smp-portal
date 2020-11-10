<?php

namespace app\rbac\rules;

use app\models\BidComment;
use app\models\User;
use yii\rbac\Rule;

class UpdateCommentRule extends Rule
{
    public $name = 'isUpdatedComment';

    public function execute($user, $item, $params)
    {
        $userModel = User::findOne($user);

        if ($userModel->role === 'admin') {
            return true;
        }

        $comment = BidComment::findOne($params['commentId']);

        if (!$comment || $comment->bid->flag_export) {
            return false;
        }

        return $comment->user_id == $user;

    }

}