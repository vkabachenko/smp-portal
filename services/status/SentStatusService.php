<?php

namespace app\services\status;

use app\models\BidStatus;

class SentStatusService extends BaseStatusService
{
    public function __construct($bidId, $user)
    {
        parent::__construct($bidId, $user);
        $this->workshopStatus = BidStatus::STATUS_SENT_WORKSHOP;
        $this->agencyStatus = BidStatus::STATUS_SENT_AGENCY;
    }

}