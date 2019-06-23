<?php

use yii\db\Migration;

/**
 * Class m190622_105520_add_fk_brand_model_id_bid_table
 */
class m190622_105520_add_fk_brand_model_id_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('ind_bid_brand_model_id', 'bid', 'brand_model_id');
        $this->addForeignKey(
            'fk_bid_brand_model_id_brand_model',
            'bid',
            'brand_model_id',
            'brand_model',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_brand_model_id_brand_model', 'bid');
        $this->dropIndex('ind_bid_brand_model_id', 'bid');
    }
}
