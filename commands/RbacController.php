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

        $manageBrand = $auth->createPermission('manageBrand');
        $auth->add($manageBrand);

        $manageCatalogs = $auth->createPermission('manageCatalogs');
        $auth->add($manageCatalogs);
        $auth->addChild($manageCatalogs, $manageBrand);

        $viewComments = $auth->createPermission('viewComments');
        $auth->add($viewComments);

        $createComment = $auth->createPermission('createComment');
        $auth->add($createComment);

        $manageEmailTemplate = $auth->createPermission('manageEmailTemplate');
        $auth->add($manageEmailTemplate);

        $client = $auth->createRole('client');
        $auth->add($client);

        $master = $auth->createRole('master');
        $auth->add($master);
        $auth->addChild($master, $listBids);
        $auth->addChild($master, $createBid);
        $auth->addChild($master, $updateBid);
        $auth->addChild($master, $viewBid);
        $auth->addChild($master, $viewComments);
        $auth->addChild($master, $createComment);

        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $listBids);
        $auth->addChild($manager, $createBid);
        $auth->addChild($manager, $updateBid);
        $auth->addChild($manager, $viewBid);
        $auth->addChild($manager, $viewComments);
        $auth->addChild($manager, $createComment);
        $auth->addChild($manager, $manageEmailTemplate);
        $auth->addChild($manager, $manageBrand);

        $director = $auth->createRole('director');
        $auth->add($director);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $listBids);
        $auth->addChild($admin, $createBid);
        $auth->addChild($admin, $updateBid);
        $auth->addChild($admin, $viewBid);
        $auth->addChild($admin, $manageCatalogs);
        $auth->addChild($admin, $viewComments);
        $auth->addChild($admin, $createComment);

        echo 'done' . "\n";
    }
}
