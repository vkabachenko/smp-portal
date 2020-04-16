<?php

use yii\db\Migration;

/**
 * Class m200414_102746_add_bid_attributes_1c_workshops_table
 */
class m200414_102746_add_bid_attributes_1c_workshops_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('workshop', 'bid_attributes_1c', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('workshop', 'bid_attributes_1c');
    }
}
