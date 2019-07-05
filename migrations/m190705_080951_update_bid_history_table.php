<?php

use yii\db\Migration;

/**
 * Class m190705_080951_update_bid_history_table
 */
class m190705_080951_update_bid_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('bid_history', 'status');
        $this->dropColumn('bid_history', 'comment');
        $this->addColumn('bid_history', 'action', 'string');
        $this->addColumn('bid_history', 'updated_attributes', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('bid_history'
            , 'status',
            "ENUM('created', 'updated', 'filled', 'sent', 'approved', 'clarification needed', 'done', 'issued', 'payed', 'closed')
            NOT NULL DEFAULT 'created'");
        $this->addColumn('bid_history', 'comment', 'text');
        $this->dropColumn('bid_history', 'action');
        $this->dropColumn('bid_history', 'updated_attributes');
    }

}
