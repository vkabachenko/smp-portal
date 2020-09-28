<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "condition".
 *
 * @property int $id
 * @property string $name
 *
 * @property Bid[] $bids
 */
class Condition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'condition';
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
     * return array
     */
    public static function conditionsAsMap($additionalValue = null)
    {
        $models = self::find()->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'name', 'name');

        if ($additionalValue) {
            $list[$additionalValue] = $additionalValue;
        }

        return $list;
    }

}
