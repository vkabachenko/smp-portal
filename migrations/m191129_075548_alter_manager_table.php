<?php

use yii\db\Migration;

/**
 * Class m191129_075548_alter_manager_table
 */
class m191129_075548_alter_manager_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_manager_manufacturer_id_manufacturer','manager');
        $this->dropColumn('manager', 'manufacturer_id');
        $this->addColumn('manager','agency_id', 'integer');
        $this->addForeignKey(
            'fk_manager_agency_id_agency',
            'manager',
            'agency_id',
            'agency',
            'id',
            'CASCADE'
        );
        $this->addColumn('manager', 'phone', 'string');
        $this->addColumn('manager','main', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('manager', 'user_id', 'integer');
        $this->addColumn('manager', 'manufacturer_id', 'integer');
        $this->addForeignKey(
            'fk_manager_user_id_user',
            'manager',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_manager_manufacturer_id_manufacturer',
            'manager',
            'manufacturer_id',
            'manufacturer',
            'id',
            'CASCADE'
        );
        $this->dropForeignKey('fk_manager_agency_id_agency', 'manager');
        $this->dropColumn('manager', 'agency_id');
        $this->dropColumn('manager', 'phone');
        $this->dropColumn('manager', 'main');
    }

}
