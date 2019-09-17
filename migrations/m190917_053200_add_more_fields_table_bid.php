<?php

use yii\db\Migration;

/**
 * Class m190917_053200_add_more_fields_table_bid
 */
class m190917_053200_add_more_fields_table_bid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bid',
            'client_type',
            "ENUM('person', 'legal_entity') NOT NULL DEFAULT 'person'"
        );
        $this->addColumn('bid','comment', $this->text());
        $this->addColumn('bid','repair_recommendations', 'string');
        $this->addColumn('bid','saler_name', 'string');
        $this->addColumn('bid','diagnostic_manufacturer', 'string');
        $this->addColumn('bid','defect_manufacturer', 'string');
        $this->addColumn('bid','date_manufacturer', 'date');
        $this->addColumn('bid','date_completion', 'date');
        $this->addColumn('bid','is_warranty_defect', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('bid','is_repair_possible', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('bid','is_for_warranty', $this->boolean()->notNull()->defaultValue(false));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bid', 'client_type');
        $this->dropColumn('bid', 'comment');
        $this->dropColumn('bid','repair_recommendations');
        $this->dropColumn('bid','saler_name');
        $this->dropColumn('bid','diagnostic_manufacturer');
        $this->dropColumn('bid','defect_manufacturer');
        $this->dropColumn('bid','date_manufacturer');
        $this->dropColumn('bid','date_completion');
        $this->dropColumn('bid','is_warranty_defect');
        $this->dropColumn('bid','is_repair_possible');
        $this->dropColumn('bid','is_for_warranty');
    }

}
