<?php

use yii\db\Migration;

/**
 * Class m201115_082039_add_imap_server_field_workshop_table
 */
class m201115_082039_add_imap_server_field_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('workshop', 'imap_server', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('workshop', 'imap_server');
    }

}
