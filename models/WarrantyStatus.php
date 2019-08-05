<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "warranty_status".
 *
 * @property int $id
 * @property string $name
 *
 * @property Bid[] $bids
 */
class WarrantyStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'warranty_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBids()
    {
        return $this->hasMany(Bid::className(), ['warranty_status_id' => 'id']);
    }

    /**
     * return array
     */
    public static function warrantyStatusAsMap()
    {
        $models = self::find()->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list;
    }


    public static function findByName($name)
    {
        if (empty($name)) {
            return null;
        }

        $model = self::find()->where(['name' => $name])->one();
        if (!$model) {
            $model = new self(['name' => $name]);
            $model->save();
        }

        return $model;
    }

    public static function findIdByName($name)
    {
        $model = self::findByName($name);

        return $model ? $model->id : null;
    }
}
