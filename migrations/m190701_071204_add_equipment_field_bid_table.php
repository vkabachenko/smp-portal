<?php

use yii\db\Migration;

/**
 * Class m190701_071204_add_equipment_field_bid_table
 */
class m190701_071204_add_equipment_field_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'equipment', 'string');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid', 'equipment');
    }

}
