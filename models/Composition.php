<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "composition".
 *
 * @property int $id
 * @property string $name
 */
class Composition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'composition';
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
            'name' => 'Name',
        ];
    }

    /**
     * return array
     */
    public static function compositions($term = null)
    {
        $models = self::find()
            ->select(['id', 'name'])
            ->andFilterWhere((['like', 'name', $term]))
            ->orderBy('name')
            ->asArray()
            ->all();

        return $models;
    }
}
