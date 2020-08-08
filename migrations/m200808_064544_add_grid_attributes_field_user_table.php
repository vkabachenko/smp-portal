<?php

use yii\db\Migration;

/**
 * Class m200808_064544_add_grid_attributes_field_user_table
 */
class m200808_064544_add_grid_attributes_field_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'grid_attributes', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'grid_attributes');
    }
}
