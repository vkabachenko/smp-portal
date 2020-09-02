<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%act_template_report_template_agency}}`.
 */
class m200831_163245_drop_act_template_report_template_agency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('agency', 'act_template');
        $this->dropColumn('agency', 'report_template');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%act_template_report_template_agency}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
