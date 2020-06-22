<?php


namespace app\commands;


use app\models\Bid;
use app\models\ClientProposition;
use app\models\ReplacementPart;
use app\models\Spare;
use yii\console\Controller;

class DbController extends Controller
{
    public function actionSetNumOrder()
    {
        $bids = Bid::find()->all();

        foreach ($bids as $bid) {
            /* @var $bid Bid */
            echo $bid->id . "\n";

            $maxNumOrder = Spare::find()->where(['bid_id' => $bid->id])->max('num_order');
            foreach ($bid->spares as $spare) {
                /* @var $spare Spare */
                if ($spare->num_order === 0) {
                    $spare->save();
                }
            }

            $maxNumOrder = ReplacementPart::find()->where(['bid_id' => $bid->id])->max('num_order');
            foreach ($bid->replacementParts as $replacementPart) {
                /* @var $replacementPart ReplacementPart */
                if ($replacementPart->num_order === 0) {
                    $maxNumOrder++;
                    $replacementPart->num_order = $maxNumOrder;
                    $replacementPart->save();
                }
            }

            $maxNumOrder = ClientProposition::find()->where(['bid_id' => $bid->id])->max('num_order');
            foreach ($bid->clientPropositions as $clientProposition) {
                /* @var $clientProposition ClientProposition */
                if ($clientProposition->num_order === 0) {
                    $maxNumOrder++;
                    $clientProposition->num_order = $maxNumOrder;
                    $clientProposition->save();
                }
            }
        }
    }

}