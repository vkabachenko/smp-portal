<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "agency_workshop".
 *
 * @property int $agency_id
 * @property int $workshop_id
 * @property int $active
 *
 * @property Agency $agency
 * @property Workshop $workshop
 */
class AgencyWorkshop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agency_workshop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agency_id', 'workshop_id'], 'required'],
            [['agency_id', 'workshop_id', 'active'], 'integer'],
            [['agency_id', 'workshop_id'], 'unique', 'targetAttribute' => ['agency_id', 'workshop_id']],
            [['agency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::className(), 'targetAttribute' => ['agency_id' => 'id']],
            [['workshop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workshop::className(), 'targetAttribute' => ['workshop_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'agency_id' => 'Agency ID',
            'workshop_id' => 'Workshop ID',
            'active' => 'Активен',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(Agency::className(), ['id' => 'agency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkshop()
    {
        return $this->hasOne(Workshop::className(), ['id' => 'workshop_id']);
    }
}
