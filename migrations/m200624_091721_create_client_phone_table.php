<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client_phone}}`.
 */
class m200624_091721_create_client_phone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%client_phone}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'phone' => $this->string()->notNull()
        ]);
        $this->addForeignKey(
            'fk_client_phone_client_id_client_id,',
            'client_phone',
            'client_id',
            'client',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_client_phone_client_id_client_id,',
            'client_phone'
        );
        $this->dropTable('{{%client_phone}}');
    }
}
