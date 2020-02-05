<?php

use yii\db\Migration;

/**
 * Class m200204_164711_add_decision_statuses_bid_table
 */
class m200204_164711_add_decision_statuses_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'decision_workshop_status_id', 'integer');
        $this->addForeignKey(
            'fk_bid_decision_workshop_status_id_decision_workshop_status',
            'bid',
            'decision_workshop_status_id',
            'decision_workshop_status',
            'id',
            'SET NULL'
        );

        $this->addColumn('bid', 'decision_agency_status_id', 'integer');
        $this->addForeignKey(
            'fk_bid_decision_agency_status_id_decision_agency_status',
            'bid',
            'decision_agency_status_id',
            'decision_agency_status',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_decision_workshop_status_id_decision_workshop_status','bid');
        $this->dropColumn('bid', 'decision_workshop_status_id');
        $this->dropForeignKey('fk_bid_decision_agency_status_id_decision_agency_status','bid');
        $this->dropColumn('bid', 'decision_agency_status_id');
    }

}
