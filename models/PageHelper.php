<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "page_helper".
 *
 * @property int $id
 * @property string $controller
 * @property string $action
 * @property string $help_text
 */
class PageHelper extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page_helper';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['controller', 'action'], 'required'],
            [['help_text'], 'string'],
            [['controller', 'action'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'controller' => 'Controller',
            'action' => 'Action',
            'help_text' => 'Введите текст помощи',
        ];
    }
}
