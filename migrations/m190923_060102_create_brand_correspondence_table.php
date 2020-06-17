<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%brand_correspondence}}`.
 */
class m190923_060102_create_brand_correspondence_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%brand_correspondence}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'brand_id' => $this->integer()
        ]);

        $this->createIndex('ind_brand_correspondence_brand_id', 'brand_correspondence', 'brand_id');
        $this->addForeignKey(
            'fk_brand_correspondence_brand_id_brand',
            'brand_correspondence',
            'brand_id',
            'brand',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_brand_correspondence_brand_id_brand', 'brand_correspondence');
        $this->dropTable('{{%brand_correspondence}}');
    }
}
