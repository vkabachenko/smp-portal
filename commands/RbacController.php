<?php


namespace app\commands;

use app\rbac\rules\ManageRestrictedBidRule;
use app\rbac\rules\ManagerBidRule;
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

        $managerBidRule = new ManagerBidRule();
        $auth->add($managerBidRule);

        $restrictBidRule = new ManageRestrictedBidRule();
        $auth->add($restrictBidRule);

        $restrictUpdateBid = $auth->createPermission('restrictUpdateBid');
        $restrictUpdateBid->ruleName = $restrictBidRule->name;
        $auth->add($restrictUpdateBid);
        $auth->addChild($restrictUpdateBid, $updateBid);

        $restrictViewBid = $auth->createPermission('restrictViewBid');
        $restrictViewBid->ruleName = $restrictBidRule->name;
        $auth->add($restrictViewBid);
        $auth->addChild($restrictViewBid, $viewBid);

        $managerViewBid = $auth->createPermission('managerViewBid');
        $managerViewBid->ruleName = $managerBidRule->name;
        $auth->add($managerViewBid);
        $auth->addChild($managerViewBid, $viewBid);

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

        $manageWorkshops = $auth->createPermission('manageWorkshops');
        $auth->add($manageWorkshops);

        $manageMasters = $auth->createPermission('manageMasters');
        $auth->add($manageMasters);

        $client = $auth->createRole('client');
        $auth->add($client);

        $master = $auth->createRole('master');
        $auth->add($master);
        $auth->addChild($master, $listBids);
        $auth->addChild($master, $createBid);
        $auth->addChild($master, $restrictUpdateBid);
        $auth->addChild($master, $restrictViewBid);
        $auth->addChild($master, $viewComments);
        $auth->addChild($master, $createComment);

        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $listBids);
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
        $auth->addChild($admin, $manageWorkshops);
        $auth->addChild($admin, $manageMasters);

        echo 'done' . "\n";
    }
}
