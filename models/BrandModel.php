<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "brand_model".
 *
 * @property int $id
 * @property int $brand_id
 * @property string $name
 *
 * @property Brand $brand
 */
class BrandModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'brand_model';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brand_id', 'name'], 'required'],
            [['brand_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::className(), 'targetAttribute' => ['brand_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Brand ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * return array
     */
    public static function brandModels($brandId)
    {
        $models = self::find()
            ->select(['id', 'name'])
            ->where(['brand_id' => $brandId])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $models;
    }

    /**
     * return array
     */
    public static function brandModelsAsMap($brandId)
    {
        $models = self::brandModels($brandId);

        return ArrayHelper::map($models, 'id', 'name');
    }
}
