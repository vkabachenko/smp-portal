<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bid_status".
 *
 * @property int $id
 * @property string $name
 * @property string $admin_name
 *
 * @property BidHistory[] $bidHistories
 */
class BidStatus extends \yii\db\ActiveRecord
{
    const STATUS_DONE = 'done';
    const STATUS_FILLED = 'filled';
    const STATUS_SENT_AGENCY = 'sent by agency';
    const STATUS_READ_AGENCY = 'read by agency';
    const STATUS_SENT_WORKSHOP = 'sent by workshop';
    const STATUS_READ_WORKSHOP = 'read by workshop';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'admin_name'], 'string', 'max' => 255],
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
            'admin_name' => 'Наименование поля в коде'
        ];
    }

    /**
     * return array
     */
    public static function bidStatusAsMap()
    {
        $models = self::find()->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list;
    }

    public static function getId($adminName)
    {
        $model = self::find()->where(['admin_name' => $adminName])->one();

        return $model ? $model->id : null;
    }
}
