<?php


namespace app\commands;

use app\rbac\rules\ManageRestrictedBidRule;
use app\rbac\rules\ManagerBidRule;
use app\rbac\rules\ManagerAgencyRule;
use app\rbac\rules\ManagerManagerRule;
use app\rbac\rules\MasterMasterRule;
use app\rbac\rules\MasterWorkshopRule;
use app\rbac\rules\BidAttributeRule;
use app\rbac\rules\SendActRule;
use app\rbac\rules\UpdateBidRule;
use app\rbac\rules\UpdateBidStatusRule;
use app\rbac\rules\ViewNewsRule;
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

        $updateAgency = $auth->createPermission('updateAgency');
        $auth->add($updateAgency);

        $managerAgencyRule = new ManagerAgencyRule();
        $auth->add($managerAgencyRule);

        $managerUpdateAgency = $auth->createPermission('managerUpdateAgency');
        $managerUpdateAgency->ruleName = $managerAgencyRule->name;
        $auth->add($managerUpdateAgency);
        $auth->addChild($managerUpdateAgency, $updateAgency);

        $managerBidRule = new ManagerBidRule();
        $auth->add($managerBidRule);

        $restrictBidRule = new ManageRestrictedBidRule();
        $auth->add($restrictBidRule);

        $updateBidRule = new UpdateBidRule();
        $auth->add($updateBidRule);

        $restrictUpdateBid = $auth->createPermission('restrictUpdateBid');
        $restrictUpdateBid->ruleName = $updateBidRule->name;
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

        $masterWorkshopRule = new MasterWorkshopRule();
        $auth->add($masterWorkshopRule);

        $masterUpdateWorkshop = $auth->createPermission('masterUpdateWorkshop');
        $masterUpdateWorkshop->ruleName = $masterWorkshopRule->name;
        $auth->add($masterUpdateWorkshop);
        $auth->addChild($masterUpdateWorkshop, $manageWorkshops);

        $manageMasters = $auth->createPermission('manageMasters');
        $auth->add($manageMasters);

        $masterMasterRule = new MasterMasterRule();
        $auth->add($masterMasterRule);

        $masterUpdateMaster = $auth->createPermission('masterUpdateMaster');
        $masterUpdateMaster->ruleName = $masterMasterRule->name;
        $auth->add($masterUpdateMaster);
        $auth->addChild($masterUpdateMaster, $manageMasters);

        $manageManagers = $auth->createPermission('manageManagers');
        $auth->add($manageManagers);

        $managerManagerRule = new ManagerManagerRule();
        $auth->add($managerManagerRule);

        $managerUpdateManager = $auth->createPermission('managerUpdateManager');
        $managerUpdateManager->ruleName = $managerManagerRule->name;
        $auth->add($managerUpdateManager);
        $auth->addChild($managerUpdateManager, $manageManagers);

        $adminBidAttribute = $auth->createPermission('adminBidAttribute');
        $auth->add($adminBidAttribute);

        $bidAttributeRule = new BidAttributeRule();
        $auth->add($bidAttributeRule);
        $manageBidAttribute = $auth->createPermission('manageBidAttribute');
        $manageBidAttribute->ruleName = $bidAttributeRule->name;
        $auth->add($manageBidAttribute);
        $auth->addChild($manageBidAttribute, $adminBidAttribute);

        $sendActRule = new SendActRule();
        $auth->add($sendActRule);
        $sendAct = $auth->createPermission('sendAct');
        $sendAct->ruleName = $sendActRule->name;
        $auth->add($sendAct);

        $updateBidStatusRule = new UpdateBidStatusRule();
        $auth->add($updateBidStatusRule);
        $updateBidStatus = $auth->createPermission('updateBidStatus');
        $updateBidStatus->ruleName = $updateBidStatusRule->name;
        $auth->add($updateBidStatus);

        $viewNewsRule = new ViewNewsRule();
        $auth->add($viewNewsRule);
        $viewNews = $auth->createPermission('viewNews');
        $viewNews->ruleName = $viewNewsRule->name;
        $auth->add($viewNews);

        $manageJobsCatalog = $auth->createPermission('manageJobsCatalog');
        $auth->add($manageJobsCatalog);

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
        $auth->addChild($master, $masterUpdateWorkshop);
        $auth->addChild($master, $masterUpdateMaster);
        $auth->addChild($master, $manageBidAttribute);
        $auth->addChild($master, $sendAct);
        $auth->addChild($master, $updateBidStatus);
        $auth->addChild($master, $viewNews);

        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $listBids);
        $auth->addChild($manager, $managerViewBid);
        $auth->addChild($manager, $viewComments);
        $auth->addChild($manager, $createComment);
        $auth->addChild($manager, $manageEmailTemplate);
        $auth->addChild($manager, $manageBrand);
        $auth->addChild($manager, $managerUpdateAgency);
        $auth->addChild($manager, $managerUpdateManager);
        $auth->addChild($manager, $manageBidAttribute);
        $auth->addChild($manager, $sendAct);
        $auth->addChild($manager, $updateBidStatus);
        $auth->addChild($manager, $viewNews);

        $director = $auth->createRole('director');
        $auth->add($director);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $listBids);
        $auth->addChild($admin, $createBid);
        $auth->addChild($admin, $updateBid);
        $auth->addChild($admin, $viewBid);
        $auth->addChild($admin, $updateAgency);
        $auth->addChild($admin, $manageCatalogs);
        $auth->addChild($admin, $viewComments);
        $auth->addChild($admin, $createComment);
        $auth->addChild($admin, $manageWorkshops);
        $auth->addChild($admin, $manageMasters);
        $auth->addChild($admin, $manageManagers);
        $auth->addChild($admin, $adminBidAttribute);
        $auth->addChild($admin, $manageJobsCatalog);

        echo 'done' . "\n";
    }
}
