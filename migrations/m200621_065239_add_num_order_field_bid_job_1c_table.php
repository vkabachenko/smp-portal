<?php

use yii\db\Migration;

/**
 * Class m200621_065239_add_num_order_field_bid_job_1c_table
 */
class m200621_065239_add_num_order_field_bid_job_1c_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid_job_1c', 'num_order', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid_job_1c', 'num_order');
    }

}
