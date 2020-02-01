<?php

use yii\db\Migration;

/**
 * Class m200201_065833_remove_email1_agency_table
 */
class m200201_065833_remove_email1_agency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('agency', 'email1');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('agency', 'email1', 'string');
    }
}
