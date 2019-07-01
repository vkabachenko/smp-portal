<?php

use yii\db\Migration;

/**
 * Class m190701_101127_append_repair_status_id_warranty_status_id_bid_table
 */
class m190701_101127_append_repair_status_id_warranty_status_id_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'repair_status_id', 'integer');
        $this->addColumn('bid', 'warranty_status_id', 'integer');

        $this->createIndex('ind_bid_repair_status_id', 'bid', 'repair_status_id');
        $this->createIndex('ind_bid_warranty_status_id', 'bid', 'warranty_status_id');

        $this->addForeignKey(
            'fk_bid_repair_status_id_repair_status',
            'bid',
            'repair_status_id',
            'repair_status',
            'id',
            'SET NULL'
        );
        $this->addForeignKey(
            'fk_bid_warranty_status_id_warranty_status',
            'bid',
            'warranty_status_id',
            'warranty_status',
            'id',
            'SET NULL'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_repair_status_id_repair_status', 'bid');
        $this->dropForeignKey('fk_bid_warranty_status_id_warranty_status', 'bid');

        $this->dropColumn('bid', 'repair_status_id');
        $this->dropColumn('bid', 'warranty_status_id');
    }


}
