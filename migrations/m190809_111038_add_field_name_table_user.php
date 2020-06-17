<?php

use yii\db\Migration;

/**
 * Class m190809_111038_add_field_name_table_user
 */
class m190809_111038_add_field_name_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('user', 'name', $this->string()->notNull());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'name');
    }

}
