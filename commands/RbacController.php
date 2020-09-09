<?php


namespace app\commands;

use app\rbac\rules\BidHistoryRule;
use app\rbac\rules\JobsCatalogRule;
use app\rbac\rules\JobsCatalogViewRule;
use app\rbac\rules\ManageJobsRule;
use app\rbac\rules\ManageReplacementPartsRule;
use app\rbac\rules\ManageRestrictedBidRule;
use app\rbac\rules\ManagerBidRule;
use app\rbac\rules\ManagerAgencyRule;
use app\rbac\rules\ManagerManagerRule;
use app\rbac\rules\MasterMasterRule;
use app\rbac\rules\MasterWorkshopRule;
use app\rbac\rules\BidAttributeRule;
use app\rbac\rules\SendActRule;
use app\rbac\rules\UpdateBidRule;
use app\rbac\rules\UpdateClientRule;
use app\rbac\rules\UpdateDecisionManagerRule;
use app\rbac\rules\UpdateDecisionMasterRule;
use app\rbac\rules\ViewImageRule;
use app\rbac\rules\ViewNewsRule;
use app\rbac\rules\ManageSpareRule;
use app\rbac\rules\ViewSpareRule;
use app\rbac\rules\SetBidDoneRule;
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

        $updateClient = $auth->createPermission('updateClient');
        $auth->add($updateClient);

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

        $updateClientRule = new UpdateClientRule();
        $auth->add($updateClientRule);

        $restrictUpdateClient = $auth->createPermission('restrictUpdateClient');
        $restrictUpdateClient->ruleName = $updateClientRule->name;
        $auth->add($restrictUpdateClient);
        $auth->addChild($restrictUpdateClient, $updateClient);

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

        $updateDecisionMasterRule = new UpdateDecisionMasterRule();
        $auth->add($updateDecisionMasterRule);
        $updateDecisionMaster = $auth->createPermission('updateDecisionMaster');
        $updateDecisionMaster->ruleName = $updateDecisionMasterRule->name;
        $auth->add($updateDecisionMaster);

        $updateDecisionManagerRule = new UpdateDecisionManagerRule();
        $auth->add($updateDecisionManagerRule);
        $updateDecisionManager = $auth->createPermission('updateDecisionManager');
        $updateDecisionManager->ruleName = $updateDecisionManagerRule->name;
        $auth->add($updateDecisionManager);

        $viewNewsRule = new ViewNewsRule();
        $auth->add($viewNewsRule);
        $viewNews = $auth->createPermission('viewNews');
        $viewNews->ruleName = $viewNewsRule->name;
        $auth->add($viewNews);

        $manageJobsCatalog = $auth->createPermission('manageJobsCatalog');
        $auth->add($manageJobsCatalog);

        $jobsCatalogRule = new JobsCatalogRule();
        $auth->add($jobsCatalogRule);
        $updateJobsCatalog = $auth->createPermission('updateJobsCatalog');
        $updateJobsCatalog->ruleName = $jobsCatalogRule->name;
        $auth->add($updateJobsCatalog);
        $auth->addChild($updateJobsCatalog, $manageJobsCatalog);

        $listJobsCatalog = $auth->createPermission('listJobsCatalog');
        $auth->add($listJobsCatalog);

        $jobsCatalogViewRule = new JobsCatalogViewRule();
        $auth->add($jobsCatalogViewRule);
        $viewJobsCatalog = $auth->createPermission('viewJobsCatalog');
        $viewJobsCatalog->ruleName = $jobsCatalogViewRule->name;
        $auth->add($viewJobsCatalog);
        $auth->addChild($viewJobsCatalog, $listJobsCatalog);

        $manageJobsRule = new ManageJobsRule();
        $auth->add($manageJobsRule);
        $manageJobs = $auth->createPermission('manageJobs');
        $manageJobs->ruleName = $manageJobsRule->name;
        $auth->add($manageJobs);

        $manageSpareRule = new ManageSpareRule();
        $auth->add($manageSpareRule);
        $manageSpare = $auth->createPermission('manageSpare');
        $manageSpare->ruleName = $manageSpareRule->name;
        $auth->add($manageSpare);

        $viewSpareRule = new ViewSpareRule();
        $auth->add($viewSpareRule);
        $viewSpare = $auth->createPermission('viewSpare');
        $viewSpare->ruleName = $viewSpareRule->name;
        $auth->add($viewSpare);

        $setBidDoneRule = new SetBidDoneRule();
        $auth->add($setBidDoneRule);
        $setBidDone = $auth->createPermission('setBidDone');
        $setBidDone->ruleName = $setBidDoneRule->name;
        $auth->add($setBidDone);

        $viewImage = $auth->createPermission('viewImage');
        $auth->add($viewImage);
        $viewImageRule = new ViewImageRule();
        $auth->add($viewImageRule);
        $viewImageRestricted = $auth->createPermission('viewImageRestricted');
        $viewImageRestricted->ruleName = $viewImageRule->name;
        $auth->add($viewImageRestricted);
        $auth->addChild($viewImageRestricted , $viewImage);

        $manageReplacementPartsRule = new ManageReplacementPartsRule();
        $auth->add($manageReplacementPartsRule);
        $manageReplacementParts = $auth->createPermission('manageReplacementParts');
        $manageReplacementParts->ruleName = $manageReplacementPartsRule->name;
        $auth->add($manageReplacementParts);

        $manageClientPropositions = $auth->createPermission('manageClientPropositions');
        $auth->add($manageClientPropositions);

        $manageBidJob1c = $auth->createPermission('manageBidJob1c');
        $auth->add($manageBidJob1c);

        $client = $auth->createRole('client');
        $auth->add($client);

        $adminBidHistory = $auth->createPermission('adminBidHistory');
        $auth->add($adminBidHistory);
        $bidHistoryRule = new BidHistoryRule();
        $auth->add($bidHistoryRule);
        $viewBidHistory = $auth->createPermission('viewBidHistory');
        $viewBidHistory->ruleName = $bidHistoryRule->name;
        $auth->add($viewBidHistory);
        $auth->addChild($viewBidHistory, $adminBidHistory);

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
        $auth->addChild($master, $updateDecisionMaster);
        $auth->addChild($master, $viewNews);
        $auth->addChild($master, $viewJobsCatalog);
        $auth->addChild($master, $manageJobs);
        $auth->addChild($master, $manageSpare);
        $auth->addChild($master, $viewSpare);
        $auth->addChild($master, $setBidDone);
        $auth->addChild($master, $viewImageRestricted);
        $auth->addChild($master, $restrictUpdateClient);
        $auth->addChild($master, $manageReplacementParts);
        $auth->addChild($master, $manageClientPropositions);
        $auth->addChild($master, $manageBidJob1c);
        $auth->addChild($master, $adminBidHistory);

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
        $auth->addChild($manager, $updateDecisionManager);
        $auth->addChild($manager, $viewNews);
        $auth->addChild($manager, $updateJobsCatalog);
        $auth->addChild($manager, $viewJobsCatalog);
        $auth->addChild($manager, $manageJobs);
        $auth->addChild($manager, $viewSpare);
        $auth->addChild($manager, $viewImageRestricted);
        $auth->addChild($manager, $viewBidHistory);

        $director = $auth->createRole('director');
        $auth->add($director);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $listBids);
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
        $auth->addChild($admin, $listJobsCatalog);
        $auth->addChild($admin, $manageJobs);
        $auth->addChild($admin, $manageSpare);
        $auth->addChild($admin, $viewSpare);
        $auth->addChild($admin, $viewImage);
        $auth->addChild($admin, $updateClient);
        $auth->addChild($admin, $manageReplacementParts);
        $auth->addChild($admin, $manageClientPropositions);
        $auth->addChild($admin, $manageBidJob1c);
        $auth->addChild($admin, $adminBidHistory);

        echo 'done' . "\n";
    }
}
