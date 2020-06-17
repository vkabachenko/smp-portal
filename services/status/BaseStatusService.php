<?php

namespace app\services\status;

use app\models\Bid;
use app\models\BidHistory;

class BaseStatusService
{
    /* @var \app\models\Bid */
    protected $bid;

    /* @var \app\models\User */
    protected $user;

    protected $workshopStatus = null;
    protected $agencyStatus = null;

    public function __construct($bidId, $user)
    {
        $this->bid = Bid::findOne($bidId);
        if (is_null($this->bid)) {
            throw new \DomainException('bid not found by id = ' . $bidId);
        }
        $this->user = $user;
    }

    public function setStatus()
    {
        $status = null;
        if ($this->user->master && $this->workshopStatus) {
            $status = $this->workshopStatus;
        } elseif ($this->user->manager && $this->agencyStatus) {
            $status = $this->agencyStatus;
        }

        if ($status) {
            $this->bid->setStatus($status);
            BidHistory::createRecord([
                'bid_id' => $this->bid->id,
                'user_id' => $this->user->id,
                'action' => 'Изменен статус заявки: ' . $status
            ]);
        }
    }

}