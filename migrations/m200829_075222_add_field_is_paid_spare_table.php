<?php

use yii\db\Migration;

/**
 * Class m200829_075222_add_field_is_paid_spare_table
 */
class m200829_075222_add_field_is_paid_spare_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('spare', 'is_paid', $this->boolean()->notNull()->defaultValue(false));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('spare', 'is_paid');
    }

}
