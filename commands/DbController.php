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
        $spares = Spare::find()->where(['num_order' => 0])-> all();

        foreach ($spares as $spare) {
                $spare->save();
        }

        echo 'spares' . "\n";

        $replacementParts = ReplacementPart::find()->where(['num_order' => 0])-> all();

        foreach ($replacementParts as $replacementPart) {
            $maxNumOrder = ReplacementPart::find()->where(['bid_id' => $replacementPart->bid_id])->max('num_order');
            $replacementPart->num_order = $maxNumOrder + 1;
            $replacementPart->save();
        }

        echo 'replacementParts' . "\n";

        $clientPropositions = ClientProposition::find()->where(['num_order' => 0])-> all();

        foreach ($clientPropositions as $clientProposition) {
            $maxNumOrder = ClientProposition::find()->where(['bid_id' => $clientProposition->bid_id])->max('num_order');
            $clientProposition->num_order = $maxNumOrder + 1;
            $clientProposition->save();
        }

        echo 'clientPropositions' . "\n";
    }

}