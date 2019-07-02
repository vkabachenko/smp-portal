<?php


namespace app\commands;

use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = \Yii::$app->authManager;
        $auth->removeAll();



        $listBids = $auth->createPermission('listBids');
        $auth->add($listBids);

        $createBid = $auth->createPermission('createBid');
        $auth->add($createBid);

        $updateBid = $auth->createPermission('updateBid');
        $auth->add($updateBid);

        $viewBid = $auth->createPermission('viewBid');
        $auth->add($viewBid);

        $manageCatalogs = $auth->createPermission('manageCatalogs');
        $auth->add($manageCatalogs);

        $client = $auth->createRole('client');
        $auth->add($client);

        $master = $auth->createRole('master');
        $auth->add($master);
        $auth->addChild($master, $listBids);
        $auth->addChild($master, $createBid);
        $auth->addChild($master, $updateBid);
        $auth->addChild($master, $viewBid);

        $manager = $auth->createRole('manager');
        $auth->add($manager);

        $director = $auth->createRole('director');
        $auth->add($director);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $listBids);
        $auth->addChild($admin, $createBid);
        $auth->addChild($admin, $updateBid);
        $auth->addChild($admin, $viewBid);
        $auth->addChild($admin, $manageCatalogs);

        echo 'done' . "\n";
    }
}
