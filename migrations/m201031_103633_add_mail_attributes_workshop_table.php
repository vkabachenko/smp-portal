<?php

use yii\db\Migration;

/**
 * Class m201031_103633_add_mail_attributes_workshop_table
 */
class m201031_103633_add_mail_attributes_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('workshop', 'mailbox_host', 'string');
        $this->addColumn('workshop', 'mailbox_pass', 'string');
        $this->addColumn('workshop', 'mailbox_port', 'string');
        $this->addColumn('workshop', 'mailbox_encryption', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('workshop', 'mailbox_host');
        $this->dropColumn('workshop', 'mailbox_pass');
        $this->dropColumn('workshop', 'mailbox_port');
        $this->dropColumn('workshop', 'mailbox_encryption');
    }

}
