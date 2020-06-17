<?php

use yii\db\Migration;

/**
 * Class m200108_155839_add_fields_workshop_table
 */
class m200108_155839_add_fields_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('workshop', 'phone1', 'string');
        $this->addColumn('workshop', 'phone2', 'string');
        $this->addColumn('workshop', 'phone3', 'string');
        $this->addColumn('workshop', 'phone4', 'string');
        $this->addColumn('workshop', 'email1', 'string');
        $this->addColumn('workshop', 'email2', 'string');
        $this->addColumn('workshop', 'email3', 'string');
        $this->addColumn('workshop', 'email4', 'string');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('workshop', 'phone1');
        $this->dropColumn('workshop', 'phone2');
        $this->dropColumn('workshop', 'phone3');
        $this->dropColumn('workshop', 'phone4');
        $this->dropColumn('workshop', 'email1');
        $this->dropColumn('workshop', 'email2');
        $this->dropColumn('workshop', 'email3');
        $this->dropColumn('workshop', 'email4');
    }
}
