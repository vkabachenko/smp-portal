<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auto_fill_attributes".
 *
 * @property int $id
 * @property int $decision_workshop_status_id
 * @property int $decision_agency_status_id
 * @property int $status_id
 * @property array $auto_fill
 *
 * @property DecisionAgencyStatus $decisionAgencyStatus
 * @property DecisionWorkshopStatus $decisionWorkshopStatus
 * @property BidStatus $status
 */
class AutoFillAttributes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auto_fill_attributes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['decision_workshop_status_id', 'decision_agency_status_id', 'status_id'], 'integer'],
            [['auto_fill'], 'safe'],
            [['decision_workshop_status_id', 'decision_agency_status_id', 'status_id'], 'unique', 'targetAttribute' => ['decision_workshop_status_id', 'decision_agency_status_id', 'status_id']],
            [['decision_agency_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => DecisionAgencyStatus::className(), 'targetAttribute' => ['decision_agency_status_id' => 'id']],
            [['decision_workshop_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => DecisionWorkshopStatus::className(), 'targetAttribute' => ['decision_workshop_status_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => BidStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'decision_workshop_status_id' => 'Решение мастерской',
            'decision_agency_status_id' => 'Решение агентства',
            'status_id' => 'Статус заявки',
            'auto_fill' => 'Auto Fill',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDecisionAgencyStatus()
    {
        return $this->hasOne(DecisionAgencyStatus::className(), ['id' => 'decision_agency_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDecisionWorkshopStatus()
    {
        return $this->hasOne(DecisionWorkshopStatus::className(), ['id' => 'decision_workshop_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(BidStatus::className(), ['id' => 'status_id']);
    }
}
