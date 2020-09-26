<?php

use yii\db\Migration;

/**
 * Class m200926_073106_add_auto_fill_field_decision_agency_status_table
 */
class m200926_073106_add_auto_fill_field_decision_agency_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('decision_agency_status', 'auto_fill', 'json');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('decision_agency_status', 'auto_fill');
    }

}
