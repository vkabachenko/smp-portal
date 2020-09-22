<?php

use yii\db\Migration;

/**
 * Class m200922_151742_remove_field_condition_id_bid_table
 */
class m200922_151742_remove_field_condition_id_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_bid_bid_condition_id_condition', 'bid');
        $this->dropColumn('bid', 'condition_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('bid', 'condition_id', 'integer');
        $this->createIndex('ind_bid_condition_id', 'bid', 'condition_id');

        $this->addForeignKey(
            'fk_bid_bid_condition_id_condition',
            'bid',
            'condition_id',
            'condition',
            'id',
            'SET NULL'
        );
    }

}
