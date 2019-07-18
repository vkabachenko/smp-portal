<?php

namespace app\models;

use app\models\form\UploadExcelTemplateForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "manufacturer".
 *
 * @property int $id
 * @property string $name
 * @property string $act_template

 *
 * @property Bid[] $bs
 * @property Brand[] $brands
 */
class Manufacturer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manufacturer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'act_template'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'act_template' => 'Файл шаблона акта'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBs()
    {
        return $this->hasMany(Bid::className(), ['manufacturer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrands()
    {
        return $this->hasMany(Brand::className(), ['manufacturer_id' => 'id']);
    }

    /**
     * return array
     */
    public static function manufacturersAsMap()
    {
        $models = self::find()->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list;
    }

    public static function getActTemplateDirectory()
    {
        return \Yii::getAlias('@app/templates/excel/act/');
    }

    /**
     * @return string
     */
    public function getActTemplatePath()
    {
        return self::getActTemplateDirectory() . $this->act_template;
    }

    /**
     * @param UploadExcelTemplateForm $uploadForm
     * @return bool
     */
    public function saveWithUpload(UploadExcelTemplateForm $uploadForm)
    {
        $this->deleteActTemplate();
        $uploadForm->file = UploadedFile::getInstance($uploadForm, 'file');
        $this->act_template = $uploadForm->file ? $uploadForm->file->name : null;
        if ($this->save()) {
            if ($uploadForm->file) {
                $uploadForm->file->saveAs($this->getActTemplatePath());
            }
            return true;
        }
        return false;
    }

    private function deleteActTemplate()
    {
        if (is_file($this->getActTemplatePath())) {
            @unlink($this->getActTemplatePath());
        }
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $this->deleteActTemplate();
        return parent::beforeDelete();
    }

    public function isActTemplate()
    {
        return !is_null($this->act_template);
    }
}
