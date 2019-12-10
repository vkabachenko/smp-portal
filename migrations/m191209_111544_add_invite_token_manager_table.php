<?php

use yii\db\Migration;

/**
 * Class m191209_111544_add_token_manager_table
 */
class m191209_111544_add_invite_token_manager_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('manager', 'invite_token', 'string');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('manager', 'invite_token');
    }

}
