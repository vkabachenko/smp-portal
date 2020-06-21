<?php

use yii\db\Migration;

/**
 * Class m200621_070022_add_num_order_replacement_part_table
 */
class m200621_070022_add_num_order_replacement_part_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('replacement_part', 'num_order', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('replacement_part', 'num_order');
    }

}
