<?php

use yii\db\Migration;

/**
 * Class m200918_084411_add_email_fields_decision_agency_status_table
 */
class m200918_084411_add_email_fields_decision_agency_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('decision_agency_status', 'sub_type_act', 'string');
        $this->addColumn('decision_agency_status', 'email_subject', 'string');
        $this->addColumn('decision_agency_status', 'email_body', 'text');
        $this->addColumn('decision_agency_status', 'email_signature', 'string');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('decision_agency_status', 'sub_type_act');
        $this->dropColumn('decision_agency_status', 'email_subject');
        $this->dropColumn('decision_agency_status', 'email_body');
        $this->dropColumn('decision_agency_status', 'email_signature');
    }

}
