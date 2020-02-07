<?php

use yii\db\Migration;

/**
 * Class m200206_155325_add_admin_name_bid_status_table
 */
class m200206_155325_add_admin_name_bid_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid_status', 'admin_name', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid_status', 'admin_name');
    }

}
