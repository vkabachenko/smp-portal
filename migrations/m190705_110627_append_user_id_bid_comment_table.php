<?php

use yii\db\Migration;

/**
 * Class m190705_110627_append_user_id_bid_comment_table
 */
class m190705_110627_append_user_id_bid_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid_comment', 'user_id', 'integer');
        $this->createIndex('ind_bid_comment_user_id', 'bid_comment', 'user_id');
        $this->addForeignKey(
            'fk_bid_comment_user_id_user',
            'bid_comment',
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
        $this->dropForeignKey('fk_bid_comment_user_id_user','bid_comment');
        $this->dropColumn('bid_comment', 'user_id');
    }
}
