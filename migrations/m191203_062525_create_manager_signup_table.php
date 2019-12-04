<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agency_signup}}`.
 */
class m191203_062525_create_manager_signup_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%manager_signup}}', [
            'id' => $this->primaryKey(),
            'agency_id' => $this->integer()->notNull(),
            'token' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'active' => $this->boolean()->notNull()->defaultValue(true)
        ]);

        $this->addForeignKey(
            'fk_manager_signup_agency_id_agency',
            'manager_signup',
            'agency_id',
            'agency',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_manager_signup_agency_id_agency', '{{%manager_signup}}');
        $this->dropTable( '{{%manager_signup}}');
    }
}
