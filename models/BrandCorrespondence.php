<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brand_correspondence".
 *
 * @property int $id
 * @property string $name
 * @property int $brand_id
 *
 * @property Bid[] $bids
 * @property Brand $brand
 */
class BrandCorrespondence extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'brand_correspondence';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
            'name' => 'Название',
            'brand_id' => 'Бренд',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBids()
    {
        return $this->hasMany(Bid::className(), ['brand_correspondence_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
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

    public function beforeDelete()
    {
        Bid::updateAll(['brand_correspondence_id' => null], ['brand_correspondence_id' => $this->id]);
        return parent::beforeDelete();
    }

    public function beforeSave($insert)
    {
        if (!$insert) {
            if ($this->brand_id) {
                $update = [
                    'brand_name' => $this->brand->name,
                    'brand_id' => $this->brand_id,
                    'manufacturer_id' => $this->brand->manufacturer_id
                ];
            } else {
                $update = [
                    'brand_name' => $this->name,
                    'brand_id' => null,
                    'manufacturer_id' => null
                ];
            }
            Bid::updateAll($update, ['brand_correspondence_id' => $this->id]);
        }
        return parent::beforeSave($insert);
    }
}
