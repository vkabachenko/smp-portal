<?php

namespace app\models;

use app\helpers\common\DateHelper;
use Yii;

/**
 * This is the model class for table "report".
 *
 * @property int $id
 * @property int $workshop_id
 * @property int $agency_id
 * @property string $report_nom
 * @property string $report_date
 * @property string $report_filename
 * @property bool $is_transferred
 *
 * @property Bid[] $bids
 * @property Agency $agency
 * @property Workshop $workshop
 */
class Report extends \yii\db\ActiveRecord
{
    /* @var array */
    public $selectedBids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['workshop_id', 'agency_id', 'report_filename'], 'required'],
            [['workshop_id', 'agency_id'], 'integer'],
            [['report_date', 'selectedBids'], 'safe'],
            [['is_transferred'], 'boolean'],
            [['report_nom', 'report_filename'], 'string', 'max' => 255],
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
            'id' => 'ID',
            'workshop_id' => 'Workshop ID',
            'agency_id' => 'Представительство',
            'report_nom' => 'Номер отчета',
            'report_date' => 'Дата отчета',
            'report_filename' => 'Report Filename',
            'is_transferred' => 'Передано',
            'selectedBids' => 'Заявки для отчета'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBids()
    {
        return $this->hasMany(Bid::className(), ['report_id' => 'id']);
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

    public function beforeValidate()
    {
        $this->report_date = DateHelper::convert($this->report_date);

        return parent::beforeValidate();
    }
}
