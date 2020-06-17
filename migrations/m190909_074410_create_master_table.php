<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%master}}`.
 */
class m190909_074410_create_master_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%master}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'workshop_id' => $this->integer()->notNull()
        ]);

        $this->createIndex('ind_master_user_id', 'master', 'user_id');
        $this->addForeignKey(
            'fk_master_user_id_user',
            'master',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
        $this->createIndex('ind_master_workshop_id', 'master', 'workshop_id');
        $this->addForeignKey(
            'fk_master_workshop_id_workshop',
            'master',
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
        $this->dropIndex('ind_master_user_id', 'master');
        $this->dropIndex('ind_master_workshop_id', 'master');
        $this->dropTable('{{%master}}');
    }
}
