<?php

use yii\db\Migration;

/**
 * Class m200201_070006_remove_email1_workshop_table
 */
class m200201_070006_remove_email1_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('workshop', 'email1');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('workshop', 'email1', 'string');
    }
}
