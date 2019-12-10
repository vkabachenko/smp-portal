<?php

use yii\db\Migration;

/**
 * Class m191210_071050_add_invite_token_master_table
 */
class m191210_071050_add_invite_token_master_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('master', 'invite_token', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('master', 'invite_token');
    }

}
