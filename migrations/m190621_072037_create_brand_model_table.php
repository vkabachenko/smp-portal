<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%brand_model}}`.
 */
class m190621_072037_create_brand_model_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%brand_model}}', [
            'id' => $this->primaryKey(),
            'brand_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull()
        ]);
        $this->createIndex('ind_brand_model_brand_id', 'brand_model', 'brand_id');
        $this->addForeignKey(
            'fk_brand_model_brand_id_brand',
            'brand_model',
            'brand_id',
            'brand',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_brand_model_brand_id_brand', 'brand_model');
        $this->dropTable('{{%brand_model}}');
    }
}
