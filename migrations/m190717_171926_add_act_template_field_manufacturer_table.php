<?php

use yii\db\Migration;

/**
 * Class m190717_171926_add_act_template_field_manufacturer_table
 */
class m190717_171926_add_act_template_field_manufacturer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('manufacturer', 'act_template', 'string');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('manufacturer', 'act_template');
    }

}
