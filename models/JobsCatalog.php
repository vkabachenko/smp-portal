<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jobs_catalog".
 *
 * @property int $id
 * @property int $agency_id
 * @property int $jobs_section_id
 * @property string $date_actual
 * @property string $vendor_code
 * @property string $name
 * @property string $description
 * @property double $hour_tariff
 * @property double $hours_required
 * @property double $price
 *
 * @property Agency $agency
 * @property JobsSection $jobsSection
 */
class JobsCatalog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jobs_catalog';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agency_id', 'date_actual', 'name', 'price'], 'required'],
            [['agency_id', 'jobs_section_id'], 'integer'],
            [['date_actual'], 'safe'],
            [['description'], 'string'],
            [['hour_tariff', 'hours_required', 'price'], 'number'],
            [['vendor_code', 'name'], 'string', 'max' => 255],
            [['agency_id', 'vendor_code'], 'unique', 'targetAttribute' => ['agency_id', 'vendor_code']],
            [['agency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::className(), 'targetAttribute' => ['agency_id' => 'id']],
            [['jobs_section_id'], 'exist', 'skipOnError' => true, 'targetClass' => JobsSection::className(), 'targetAttribute' => ['jobs_section_id' => 'id']],
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
            'jobs_section_id' => 'Раздел работ',
            'date_actual' => 'Дата актуальности',
            'vendor_code' => 'Артикул',
            'name' => 'Наименование',
            'description' => 'Описание',
            'hour_tariff' => 'Цена нормочаса',
            'hours_required' => 'Нормочасов',
            'price' => 'Стоимость',
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
    public function getJobsSection()
    {
        return $this->hasOne(JobsSection::className(), ['id' => 'jobs_section_id']);
    }
}
