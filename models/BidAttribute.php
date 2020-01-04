<?php

namespace app\models;

use app\models\query\BidAttributeQuery;
use Yii;

/**
 * This is the model class for table "bid_attribute".
 *
 * @property int $id
 * @property string $attribute
 * @property string $description
 * @property string $short_description
 * @property int $is_disabled_agencies
 * @property int $is_disabled_workshops
 */
class BidAttribute extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid_attribute';
    }

    public static function find()
    {
        return new BidAttributeQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['is_disabled_agencies', 'is_disabled_workshops'], 'integer'],
            [['attribute', 'short_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'attribute' => 'Поле',
            'description' => 'Описание',
            'short_description' => 'Краткое описание',
            'is_disabled_agencies' => 'Скрыть для представительств',
            'is_disabled_workshops' => 'Скрыть для мастерских',
        ];
    }

    public static function getEnabledAttributes()
    {
        $attributes = self::find()->active()->select(['attribute'])->column();
        return self::getAttributeIds($attributes);
    }

    public static function getHiddenAttributes($attribute)
    {
        return self::find()->active()->select(['attribute'])->where([$attribute => true])->column();
    }

    public static function getAvailableAttributes($attribute)
    {
        $hiddenAttributes = self::getHiddenAttributes($attribute);
        return array_keys(self::getAttributeIds($hiddenAttributes));
    }

    public static function getHints()
    {
        $models = self::find()
            ->select(['attribute', 'short_description', 'description'])
            ->indexBy('attribute')
            ->asArray()
            ->all();

        return $models;
    }

    /**
     * @param $attributes[] BidAttribute
     * @return array
     */
    private static function getAttributeIds($attributes)
    {
        return array_filter(Bid::EDITABLE_ATTRIBUTES, function($value, $key) use ($attributes) {
            return !in_array($key, $attributes);
        }, ARRAY_FILTER_USE_BOTH);
    }

}
