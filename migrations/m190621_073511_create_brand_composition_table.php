<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%brand_composition}}`.
 */
class m190621_073511_create_brand_composition_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%brand_composition}}', [
            'id' => $this->primaryKey(),
            'brand_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull()
        ]);
        $this->createIndex('ind_brand_composition_brand_id', 'brand_composition', 'brand_id');
        $this->addForeignKey(
            'fk_brand_composition_brand_id_brand',
            'brand_composition',
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
        $this->dropForeignKey('fk_brand_composition_brand_id_brand', 'brand_composition');
        $this->dropTable('{{%brand_composition}}');
    }
}
