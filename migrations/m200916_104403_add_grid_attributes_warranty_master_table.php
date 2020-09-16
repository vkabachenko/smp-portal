<?php

use yii\db\Migration;

/**
 * Class m200916_104403_add_grid_attributes_warranty_master_table
 */
class m200916_104403_add_grid_attributes_warranty_master_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('master', 'grid_attributes_warranty', 'text');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('master', 'grid_attributes_warranty');
    }
}
