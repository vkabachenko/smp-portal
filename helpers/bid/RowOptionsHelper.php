<?php


namespace app\helpers\bid;


use app\models\Bid;
use app\models\BidHistory;

class RowOptionsHelper
{
    public static function getClass($bidId, $userRole)
    {
        if (BidHistory::isBidDone($bidId)) {
            return 'bid-done disabled enabled-events';
        }

        $sentBidStatus = BidHistory::sentBidStatus($bidId);

        switch ($userRole) {
            case 'manager':
                if ($sentBidStatus == BidHistory::BID_SENT_AGENCY) {
                    return 'disabled enabled-events';
                } else {
                    return BidHistory::isBidViewed($bidId, $userRole) === false ? 'not-viewed' : '';
                }
            case 'master': {
                if ($sentBidStatus == BidHistory::BID_SENT_WORKSHOP) {
                    return 'disabled enabled-events';
                } else {
                    return BidHistory::isBidViewed($bidId, $userRole) === false ? 'not-viewed' : '';
                }
            }
            default: {
                return '';
            }
        }

    }

}