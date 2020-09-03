<?php

use yii\db\Migration;

/**
 * Class m200903_103500_add_uuid_field_master_table
 */
class m200903_103500_add_uuid_field_master_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('master', 'uuid', 'string');
        $this->createIndex(
'ind_master_uuid_unique',
'master',
'uuid',
true
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('master', 'uuid');
    }

}
