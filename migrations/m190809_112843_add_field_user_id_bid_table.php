<?php

use yii\db\Migration;

/**
 * Class m190809_112843_add_field_user_id_bid_table
 */
class m190809_112843_add_field_user_id_bid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid', 'user_id', $this->integer());
        $this->createIndex('ind_bid_user_id', 'bid', 'user_id');
        $this->addForeignKey(
            'fk_bid_user_id_user',
            'bid',
            'user_id',
            'user',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_bid__user_id_user','bid');
        $this->dropColumn('bid', 'user_id');
    }
}
