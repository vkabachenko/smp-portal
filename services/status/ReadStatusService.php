<?php

namespace app\services\status;

use app\models\BidStatus;

class ReadStatusService extends BaseStatusService
{
    public function __construct($bidId, $user)
    {
        parent::__construct($bidId, $user);
        $this->workshopStatus = BidStatus::STATUS_READ_WORKSHOP;
        $this->agencyStatus = BidStatus::STATUS_READ_AGENCY;
    }

}