<?php

use yii\db\Migration;

/**
 * Class m200422_083938_add_act_template_report_template_agency_table
 */
class m200422_083938_add_act_template_report_template_agency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agency', 'act_template', 'string');
        $this->addColumn('agency', 'report_template', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('agency', 'act_template', 'string');
        $this->addColumn('agency', 'report_template', 'string');
    }
}
