<?php

use yii\db\Migration;

/**
 * Class m200616_073840_add_is_independent_field_agency_table
 */
class m200616_073840_add_is_independent_field_agency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agency', 'is_independent', $this->boolean()->notNull()->defaultValue(false));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('agency', 'is_independent');
    }

}
