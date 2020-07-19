<?php


namespace app\rbac\rules;

use app\models\Client;
use app\models\Master;
use yii\rbac\Rule;

class UpdateClientRule extends Rule
{
    public $name = 'isUpdatedClient';

    public function execute($user, $item, $params)
    {
        if (!isset($params['clientId'])) {
            return true;
        }

        /* @var $master Master */
        $master = Master::findByUserId($user);

        $client = Client::findOne($params['clientId']);

        return $master && $master->workshop_id === $client->workshop_id;
    }

}