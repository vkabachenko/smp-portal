<?php

namespace app\models;

use app\models\form\UploadExcelTemplateForm;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use SimpleXLSX;

/**
 * This is the model class for table "jobs_catalog".
 *
 * @property int $id
 * @property string $uuid
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
            [['agency_id', 'date_actual', 'name', 'price', 'uuid'], 'required'],
            [['agency_id', 'jobs_section_id'], 'integer'],
            [['description', 'uuid'], 'string'],
            [['hour_tariff', 'hours_required', 'price'], 'number'],
            [['vendor_code', 'name'], 'string', 'max' => 255],
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

    public function getBidJobs()
    {
        return $this->hasMany(BidJob::class, ['jobs_catalog_id' => 'id']);
    }

    public static function addFromExcel($agencyId, UploadExcelTemplateForm $uploadForm)
    {
        $path = \Yii::getAlias('@runtime/jobs-catalog-' . $agencyId . '.xlsx');
        $uploadForm->file = UploadedFile::getInstance($uploadForm, 'file');
        if (!$uploadForm->file) {
            throw new \DomainException('xlsx file not uploaded');
        }
        $uploadForm->file->saveAs($path);
        $xlsx = SimpleXLSX::parse($path);

        if (!$xlsx) {
            throw new \DomainException(SimpleXLSX::parseError());
        }

        $rows = $xlsx->rows();
        array_shift($rows);


        /*row:
        0 - Раздел работ
        1 - Артикул
        2 -	Наименование
        3 - Описание
        4 -	Цена нормочаса
        5 -	Нормочасов
        6 -	Стоимость
        */

        foreach ($rows as $row) {

            $sectionName = strval($row[0]);
            $jobsSection = JobsSection::find()->where(['name' => $sectionName])->one();
            if (is_null($jobsSection)) {
                $jobsSection = new JobsSection(['agency_id' => $agencyId, 'name' => $sectionName]);
                $jobsSection->save();
            }

            $model = new self();

            $model->agency_id = $agencyId;
            $model->date_actual = '1970-01-01';
            $model->uuid = \Yii::$app->security->generateRandomString();
            $model->jobs_section_id = $jobsSection->id;
            $model->vendor_code = strval($row[1]);
            $model->name = strval($row[2]);
            $model->description = strval($row[3]);
            $model->hour_tariff = doubleval($row[4]);
            $model->hours_required = doubleval($row[5]);
            $model->price = doubleval($row[6]);

            if (!$model->save()) {
                \Yii::error($model->getErrors());
                \Yii::error($row);
            }
        }
    }

    /**
     * return array
     */
    public static function jobsSectionAsMap($sectionId, $agencyId)
    {
        $models = self::find()->where(['jobs_section_id' => $sectionId, 'agency_id' => $agencyId])->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list;
    }

}
