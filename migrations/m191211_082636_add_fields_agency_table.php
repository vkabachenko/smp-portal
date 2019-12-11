<?php

use yii\db\Migration;

/**
 * Class m191211_082636_add_fields_agency_table
 */
class m191211_082636_add_fields_agency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agency', 'phone1', 'string');
        $this->addColumn('agency', 'phone2', 'string');
        $this->addColumn('agency', 'phone3', 'string');
        $this->addColumn('agency', 'phone4', 'string');
        $this->addColumn('agency', 'email1', 'string');
        $this->addColumn('agency', 'email2', 'string');
        $this->addColumn('agency', 'email3', 'string');
        $this->addColumn('agency', 'email4', 'string');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('agency', 'phone1');
        $this->dropColumn('agency', 'phone2');
        $this->dropColumn('agency', 'phone3');
        $this->dropColumn('agency', 'phone4');
        $this->dropColumn('agency', 'email1');
        $this->dropColumn('agency', 'email2');
        $this->dropColumn('agency', 'email3');
        $this->dropColumn('agency', 'email4');
    }

}
