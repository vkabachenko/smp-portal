<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%manager}}`.
 */
class m190924_101303_create_manager_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%manager}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'manufacturer_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey(
            'fk_manager_user_id_user',
            'manager',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
        $this->createIndex('ind_manager_workshop_id', 'manager', 'manufacturer_id');
        $this->addForeignKey(
            'fk_manager_manufacturer_id_manufacturer',
            'manager',
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
        $this->dropForeignKey('fk_manager_manufacturer_id_manufacturer','manager');
        $this->dropTable('{{%manager}}');
    }
}
