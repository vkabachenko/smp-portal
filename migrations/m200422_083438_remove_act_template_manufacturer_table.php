<?php

use yii\db\Migration;

/**
 * Class m200422_083438_remove_act_template_manufacturer_table
 */
class m200422_083438_remove_act_template_manufacturer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('manufacturer', 'act_template');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('manufacturer', 'act_template', 'string');
    }
}
