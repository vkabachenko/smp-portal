<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%decision_workshop_status}}`.
 */
class m200204_144529_create_decision_workshop_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%decision_workshop_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%decision_workshop_status}}');
    }
}
