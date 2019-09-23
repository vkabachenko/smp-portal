<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "brand".
 *
 * @property int $id
 * @property int $manufacturer_id
 * @property string $name
 *
 * @property Bid[] $bs
 * @property Manufacturer $manufacturer
 * @property BrandComposition[] $brandCompositions
 * @property BrandModel[] $brandModels
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manufacturer_id', 'name'], 'required'],
            [['manufacturer_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::className(), 'targetAttribute' => ['manufacturer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manufacturer_id' => 'Manufacturer ID',
            'name' => 'Наименование',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBids()
    {
        return $this->hasMany(Bid::className(), ['brand_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturer::className(), ['id' => 'manufacturer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandCompositions()
    {
        return $this->hasMany(BrandComposition::className(), ['brand_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrandModels()
    {
        return $this->hasMany(BrandModel::className(), ['brand_id' => 'id']);
    }

    /**
     * return array
     */
    public static function brandsAsMap()
    {
        $models = self::find()->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list;
    }


    /**
     * return array
     */
    public static function brandsManufacturer($manufacturerId)
    {
        $models = self::find()
            ->select(['id', 'name'])
            ->where(['manufacturer_id' => $manufacturerId])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $models;
    }

    /**
     * return array
     */
    public static function brandsManufacturerAsMap($manufacturerId)
    {
        $models = self::brandsManufacturer($manufacturerId);

        return ArrayHelper::map($models, 'id', 'name');
    }

    public static function findByName($name)
    {
        if (empty($name)) {
            return null;
        }

        $model = self::find()->where(['name' => $name])->one();
        if (!$model) {
            return null;
        }

        return $model;
    }

}
