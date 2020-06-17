<?php

use yii\db\Migration;

/**
 * Class m190630_060622_add_updated_status_field_bid_history_table
 */
class m190630_060622_add_updated_status_field_bid_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('bid_history', 'status');
        $this->addColumn('bid_history'
            , 'status',
            "ENUM('created', 'updated', 'filled', 'sent', 'approved', 'clarification needed', 'done', 'issued', 'payed', 'closed')
            NOT NULL DEFAULT 'created'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid_history', 'status');
        $this->addColumn('bid_history'
            , 'status',
            "ENUM('created', 'filled', 'sent', 'approved', 'clarification needed', 'done', 'issued', 'payed', 'closed')
            NOT NULL DEFAULT 'created'");
    }

}
