<?php

use yii\db\Migration;

/**
 * Class m200922_071102_add_condition_name_field_bid_table
 */
class m200922_071102_add_condition_name_field_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'condition_name', 'string');
        $this->addColumn('bid', 'condition_manufacturer_name', 'string');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid', 'condition_name');
        $this->addColumn('bid', 'condition_manufacturer_name');
    }

}
