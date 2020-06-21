<?php

use yii\db\Migration;

/**
 * Class m200621_070100_add_num_order_client_proposition_table
 */
class m200621_070100_add_num_order_client_proposition_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('client_proposition', 'num_order', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('client_proposition', 'num_order');
    }

}
