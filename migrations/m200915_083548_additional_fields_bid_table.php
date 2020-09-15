<?php

use yii\db\Migration;

/**
 * Class m200915_083548_additional_fields_bid_table
 */
class m200915_083548_additional_fields_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid','date_completion_manufacturer', 'date');
        $this->addColumn('bid','composition_name_manufacturer' , 'string');
        $this->addColumn('bid', 'condition_manufacturer_id', 'integer');
        $this->addColumn('bid', 'client_manufacturer_id', 'integer');


        $this->createIndex('ind_bid_condition_manufacturer_id', 'bid', 'condition_manufacturer_id');
        $this->createIndex('ind_bid_client_manufacturer_id', 'bid', 'client_manufacturer_id');

        $this->addForeignKey(
            'fk_bid_bid_condition_manufacturer_id_condition',
            'bid',
            'condition_manufacturer_id',
            'condition',
            'id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk_bid_client_manufacturer_id_client',
            'bid',
            'client_id',
            'client',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid','date_completion_manufacturer');
        $this->dropColumn('bid','composition_name_manufacturer');
        $this->dropForeignKey('fk_bid_bid_condition_manufacturer_id_condition', 'bid');
        $this->dropColumn('bid', 'condition_manufacturer_id');
        $this->dropForeignKey('fk_bid_client_manufacturer_id_client', 'bid');
        $this->dropColumn('bid', 'client_manufacturer_id');
    }

}
