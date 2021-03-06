<?php

namespace app\models;

use app\helpers\common\DateHelper;
use Yii;

/**
 * This is the model class for table "agency_workshop".
 *
 * @property int $id
 * @property int $agency_id
 * @property int $workshop_id
 * @property int $active
 * @property string $contract_nom
 * @property string $contract_date
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
            [['agency_id', 'workshop_id'], 'integer'],
            [['contract_nom', 'contract_date'], 'string'],
            ['active', 'boolean'],
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
            'contract_nom' => 'Номер договора',
            'contract_date' => 'Дата договора'
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

    public static function getActive(Agency $agency, Workshop $workshop)
    {
        $model = self::find()->where(['agency_id' => $agency->id, 'workshop_id' => $workshop->id])->one();
        if ($model) {
            return (bool)$model->active;
        } else {
            return null;
        }
    }

    public static function getOfficialDoc(Agency $agency, Workshop $workshop)
    {
        $model = self::find()->where(['agency_id' => $agency->id, 'workshop_id' => $workshop->id])->one();
        if ($model) {
            $officialDoc = OfficialDocs::find()->where(['model' => 'AgencyWorkshop', 'model_id' => $model->id])->one();
            return $officialDoc;
        } else {
            throw new \DomainException('AgencyWorkshop model not found');
        }
    }

    public function beforeValidate()
    {
        $this->contract_date = DateHelper::convert($this->contract_date);
        return parent::beforeValidate();
    }
}
