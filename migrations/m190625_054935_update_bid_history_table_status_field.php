<?php

use yii\db\Migration;

/**
 * Class m190625_054935_update_bid_history_table_status_field
 */
class m190625_054935_update_bid_history_table_status_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_bid_history_status_id', 'bid_history');
        $this->dropColumn('bid_history', 'status_id');
        $this->addColumn('bid_history'
            , 'status',
            "ENUM('created', 'filled', 'sent', 'approved', 'clarification needed', 'done', 'issued', 'payed', 'closed')
            NOT NULL DEFAULT 'created'");


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid_history', 'status');
        $this->addColumn('bid_history', 'status_id', 'integer');
        $this->createIndex('ind_bid_history_status_id', 'bid_history', 'status_id');
        $this->addForeignKey(
            'fk_bid_history_status_id',
            'bid_history',
            'status_id',
            'bid_status',
            'id',
            'SET NULL'
        );
    }

}
