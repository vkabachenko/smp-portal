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

        $userImageModel = $bidImage->user;

        if (is_null($userImageModel)) {
            return true;
        }

        /* @var $userModel \app\models\User */
        $userModel = User::findOne($user);

        if ($userModel->master && $userImageModel->master) {
            return ($userModel->master->workshop_id == $userImageModel->master->workshop_id);
        } elseif ($userModel->manager && $userImageModel->manager) {
            return ($userModel->manager->agency_id == $userImageModel->manager->agency_id);
        } else {
            return false;
        }
    }
}