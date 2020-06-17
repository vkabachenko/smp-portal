<?php

use yii\db\Migration;

/**
 * Class m190621_104648_add_created_at_updated_at_bid_history_table
 */
class m190621_104648_add_created_at_updated_at_bid_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid_history','created_at', $this->dateTime());
        $this->addColumn('bid_history','updated_at', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid_history','created_at');
        $this->dropColumn('bid_history','updated_at');
    }

}
