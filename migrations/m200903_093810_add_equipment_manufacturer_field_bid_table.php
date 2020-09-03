<?php

use yii\db\Migration;

/**
 * Class m200903_093810_add_equipment_manufacturer_field_bid_table
 */
class m200903_093810_add_equipment_manufacturer_field_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'equipment_manufacturer', 'string');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid', 'equipment_manufacturer');
    }

}
