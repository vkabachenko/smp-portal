<?php

use yii\db\Migration;

/**
 * Class m190807_063556_alter_manufacturer_id_bid_table
 */
class m190807_063556_alter_manufacturer_id_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('bid', 'manufacturer_id', $this->integer());
        $this->dropForeignKey('fk_bid_manufacturer_id_manufacturer', 'bid');
        $this->addForeignKey(
            'fk_bid_manufacturer_id_manufacturer',
            'bid',
            'manufacturer_id',
            'manufacturer',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('bid', 'manufacturer_id', $this->integer()->notNull());
        $this->dropForeignKey('fk_bid_manufacturer_id_manufacturer', 'bid');
        $this->addForeignKey(
            'fk_bid_manufacturer_id_manufacturer',
            'bid',
            'manufacturer_id',
            'manufacturer',
            'id',
            'CASCADE'
        );
    }

}
