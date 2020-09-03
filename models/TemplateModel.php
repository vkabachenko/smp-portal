<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property int $id
 * @property int $agency_id
 * @property string $type
 * @property string $sub_type
 * @property string $file_name
 * @property string $email_subject
 * @property string $email_body
 * @property string $email_signature
 *
 * @property Agency $agency
 */
class TemplateModel extends \yii\db\ActiveRecord
{
    const TYPE_ACT = 'act';
    const TYPE_REPORT = 'report';
    const SUB_TYPE_ACT_DIAGNOSTIC = 'act_diagnostic';
    const SUB_TYPE_ACT_WRITE_OFF = 'act_write_off';
    const SUB_TYPE_ACT_NO_WARRANTY = 'act_no_warranty';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agency_id', 'type'], 'required'],
            [['agency_id'], 'integer'],
            [['type', 'sub_type', 'file_name', 'email_subject', 'email_signature'], 'string', 'max' => 255],
            [['email_body'], 'string'],
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
            'type' => 'Type',
            'sub_type' => 'Sub Type',
            'file_name' => 'File Name',
            'email_subject' => 'Шаблон письма - тема',
            'email_body' => 'Шаблон письма - текст',
            'email_signature' => 'Шаблон письма - подпись',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(Agency::className(), ['id' => 'agency_id']);
    }

    public function getHeader()
    {
        switch ($this->type) {
            case self::TYPE_ACT:
                return $this->getActHeader();
            case self::TYPE_REPORT:
                return $this->getReportHeader();
            default:
                return '';
        }
    }

    public function getActHeader()
    {
        switch ($this->sub_type) {
            case self::SUB_TYPE_ACT_DIAGNOSTIC:
                return 'Акт диагностики';
            case self::SUB_TYPE_ACT_WRITE_OFF:
                return 'Акт списания';
            case self::SUB_TYPE_ACT_NO_WARRANTY:
                return 'Акт не гарантии';
            default:
                return 'Акт';
        }
    }

    public function getReportHeader()
    {
        return 'Отчет';
    }

    public function getFilename()
    {
        return ($this->sub_type ?: $this->type) . '.xlsx';
    }

    public function getTemplateDirectory()
    {
        $dir = \Yii::getAlias('@app/templates/excel/' . $this->type . '/' . $this->agency_id . '/');

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        return $dir;
    }

    /**
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->getTemplateDirectory() . $this->file_name;
    }
}
