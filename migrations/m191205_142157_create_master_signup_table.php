<?php

use yii\db\Migration;

/**
 * Class m191205_142157_master_signup_table
 */
class m191205_142157_create_master_signup_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%master_signup}}', [
            'id' => $this->primaryKey(),
            'workshop_id' => $this->integer()->notNull(),
            'token' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'active' => $this->boolean()->notNull()->defaultValue(true)
        ]);

        $this->addForeignKey(
            'fk_master_signup_workshop_id_workshop',
            'master_signup',
            'workshop_id',
            'workshop',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_master_signup_workshop_id_workshop', '{{%master_signup}}');
        $this->dropTable( '{{%master_signup}}');
    }

}
