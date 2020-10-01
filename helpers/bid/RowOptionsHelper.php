<?php


namespace app\helpers\bid;

use app\models\Bid;
use app\models\BidStatus;
use app\models\User;

class RowOptionsHelper
{
    public static function getClass(Bid $bid, User $user)
    {
        $userRole = $user->role;

        if ($userRole === 'admin') {
            return '';
        }

        if ($userRole === 'master' && $user->master->getBidRole() === Bid::TREATMENT_TYPE_PRESALE) {
            return '';
        }

        if ($bid->status_id == BidStatus::getId(BidStatus::STATUS_DONE)) {
            return 'bid-done disabled enabled-events';
        }

        switch ($userRole) {
            case 'manager':
                if ($bid->status_id == BidStatus::getId(BidStatus::STATUS_SENT_AGENCY) ||
                    $bid->status_id == BidStatus::getId(BidStatus::STATUS_READ_WORKSHOP)) {
                    return 'disabled enabled-events';
                } else {
                    return $bid->status_id == BidStatus::getId(BidStatus::STATUS_SENT_WORKSHOP) ? 'not-viewed' : '';
                }
            case 'master':
                if (($bid->status_id == BidStatus::getId(BidStatus::STATUS_SENT_WORKSHOP)  && !$bid->getAgency()->is_independent) ||
                    $bid->status_id == BidStatus::getId(BidStatus::STATUS_READ_AGENCY)) {
                    return 'disabled enabled-events';
                } else {
                    return $bid->status_id == BidStatus::getId(BidStatus::STATUS_SENT_AGENCY) ? 'not-viewed' : '';
                }
            default: {
                return '';
            }
        }
    }

}