<?php

use yii\db\Migration;

/**
 * Class m200908_103546_remove_field_is_paid_spare_table
 */
class m200908_103546_remove_field_is_paid_spare_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('spare', 'is_paid');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('spare', 'is_paid', $this->boolean()->notNull()->defaultValue(false));
    }

}
