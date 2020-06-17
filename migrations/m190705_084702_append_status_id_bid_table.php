<?php

use yii\db\Migration;

/**
 * Class m190705_084702_append_status_id_bid_table
 */
class m190705_084702_append_status_id_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'status_id', 'integer');
        $this->createIndex('ind_bid_status_id', 'bid', 'status_id');
        $this->addForeignKey(
            'fk_bid_status_id_bid_status',
            'bid',
            'status_id',
            'bid_status',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_status_id_bid_status', 'bid');
        $this->dropColumn('bid', 'status_id');
    }

}
