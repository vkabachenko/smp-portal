<?php

use yii\db\Migration;

/**
 * Class m200922_151927_remove_field_condition_manufacturer_id_bid_table
 */
class m200922_151927_remove_field_condition_manufacturer_id_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_bid_bid_condition_manufacturer_id_condition', 'bid');
        $this->dropColumn('bid', 'condition_manufacturer_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('bid', 'condition_manufacturer_id', 'integer');

        $this->createIndex('ind_bid_condition_manufacturer_id', 'bid', 'condition_manufacturer_id');

        $this->addForeignKey(
            'fk_bid_bid_condition_manufacturer_id_condition',
            'bid',
            'condition_manufacturer_id',
            'condition',
            'id',
            'SET NULL'
        );

    }

}
