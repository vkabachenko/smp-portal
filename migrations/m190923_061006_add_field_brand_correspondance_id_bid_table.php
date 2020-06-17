<?php

use yii\db\Migration;

/**
 * Class m190923_061006_add_field_brand_correspondance_id_bid_table
 */
class m190923_061006_add_field_brand_correspondance_id_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'brand_correspondence_id', 'integer');
        $this->createIndex('ind_bid_brand_correspondence_id', 'bid', 'brand_correspondence_id');
        $this->addForeignKey(
            'fk_bid_brand_correspondence_id_brand_correspondence',
            'bid',
            'brand_correspondence_id',
            'brand_correspondence',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid_brand_correspondence_id_brand_correspondence', 'bid');
        $this->dropColumn('bid', 'brand_correspondance_id');
    }

}
