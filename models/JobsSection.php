<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "jobs_section".
 *
 * @property int $id
 * @property int $agency_id
 * @property string $name
 *
 * @property JobsCatalog[] $jobsCatalogs
 * @property Agency $agency
 */
class JobsSection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jobs_section';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agency_id', 'name'], 'required'],
            [['agency_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['agency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::className(), 'targetAttribute' => ['agency_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'agency_id' => 'Agency ID',
            'name' => 'Наименование',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobsCatalogs()
    {
        return $this->hasMany(JobsCatalog::className(), ['jobs_section_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(Agency::className(), ['id' => 'agency_id']);
    }

    /**
     * return array
     */
    public static function jobsSectionAsMap($agencyId, $withEmpty = false)
    {
        $models = self::find()->where(['agency_id' => $agencyId])->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $withEmpty ? ['0' => 'Все'] + $list : $list;
    }
}
