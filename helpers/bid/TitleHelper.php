<?php

namespace app\helpers\bid;

use app\models\User;

class TitleHelper
{
    public static function getTitle(User $user)
    {
        $role = $user->role;

        switch($role) {
            case 'master':
                return 'Личный кабинет мастера';
            case 'manager':
                return 'Личный кабинет менеджера';
            case 'admin':
                return 'Личный кабинет администратора';
            default:
                return 'Список заявок';
        }
    }


}