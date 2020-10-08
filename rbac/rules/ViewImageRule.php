<?php


namespace app\rbac\rules;

use app\models\BidImage;
use app\models\User;
use yii\rbac\Rule;

class ViewImageRule extends Rule
{
    public $name = 'isViewImage';

    public function execute($user, $item, $params)
    {
        /* @var $bidImage \app\models\BidImage */
        $bidImage = BidImage::findOne($params['imageId']);

        if (is_null($bidImage)) {
            return false;
        }

        if ($bidImage->sent) {
            return true;
        }

        /* @var $userModel \app\models\User */
        $userModel = User::findOne($user);

        if ($userModel->manager && !$bidImage->user->manager) {
            return $bidImage->sent;
        } else {
            return true;
        }

    }
}