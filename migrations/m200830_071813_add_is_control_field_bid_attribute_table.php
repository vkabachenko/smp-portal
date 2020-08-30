<?php

use yii\db\Migration;

/**
 * Class m200830_071813_add_is_control_field_bid_attribute_table
 */
class m200830_071813_add_is_control_field_bid_attribute_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid_attribute', 'is_control', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid_attribute', 'is_control');
    }

}
