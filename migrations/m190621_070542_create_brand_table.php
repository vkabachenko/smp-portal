<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%brand}}`.
 */
class m190621_070542_create_brand_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%brand}}', [
            'id' => $this->primaryKey(),
            'manufacturer_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull()
        ]);
        $this->createIndex('ind_brand_manufacturer_id', 'brand', 'manufacturer_id');
        $this->addForeignKey(
            'fk_brand_manufacturer_id_manufacturer',
            'brand',
            'manufacturer_id',
            'manufacturer',
            'id',
            'CASCADE'
            );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_brand_manufacturer_id_manufacturer', 'brand');
        $this->dropTable('{{%brand}}');
    }
}
