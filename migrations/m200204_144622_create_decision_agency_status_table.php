<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%decision_agency_status}}`.
 */
class m200204_144622_create_decision_agency_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%decision_agency_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%decision_agency_status}}');
    }
}
