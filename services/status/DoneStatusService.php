<?php

namespace app\services\status;

use app\models\BidStatus;

class DoneStatusService extends BaseStatusService
{
    public function __construct($bidId, $user)
    {
        parent::__construct($bidId, $user);
        $this->workshopStatus = BidStatus::STATUS_DONE;
    }

}