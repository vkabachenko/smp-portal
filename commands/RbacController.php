<?php


namespace app\commands;

use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();

        // добавляем роль "author" и даём роли разрешение "createPost"
        $client = $auth->createRole('client');
        $auth->add($client);

        $master = $auth->createRole('master');
        $auth->add($master);

        $manager = $auth->createRole('manager');
        $auth->add($manager);

        $director = $auth->createRole('director');
        $auth->add($director);

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        echo 'done' . "\n";
    }
}
