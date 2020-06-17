<?php

use yii\db\Migration;

/**
 * Class m200429_061955_add_contract_creds_agency_workshop_table
 */
class m200429_061955_add_contract_creds_agency_workshop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agency_workshop', 'contract_nom', 'string');
        $this->addColumn('agency_workshop', 'contract_date', 'date');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('agency_workshop', 'contract_nom');
        $this->dropColumn('agency_workshop', 'contract_date');
    }

}
