<?php

namespace app\models;

use app\helpers\constants\Constants;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "repair_status".
 *
 * @property int $id
 * @property string $name
 *
 * @property Bid[] $bids
 */
class RepairStatus extends \yii\db\ActiveRecord
{
    const DIAGNOSTIC_NAME = '1.Диагностика';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'repair_status';
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
        return $this->hasMany(Bid::className(), ['repair_status_id' => 'id']);
    }

    /**
     * return array
     */
    public static function repairStatusAsMap($withEmpty = false)
    {
        $models = self::find()->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $withEmpty ? Constants::EMPTY_ELEMENT + $list : $list;
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
