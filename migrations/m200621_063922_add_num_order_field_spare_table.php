<?php

use yii\db\Migration;

/**
 * Class m200621_063922_add_num_order_field_spare_table
 */
class m200621_063922_add_num_order_field_spare_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('spare', 'num_order', $this->integer()->notNull()->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('spare', 'num_order');
    }

}
