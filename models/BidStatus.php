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
    const AGENCY_STATUSES = [];
    const WORKSHOP_STATUSES = [
      self::STATUS_DONE
    ];
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
    public static function bidStatusAsMapForAdmin()
    {
        $models = self::find()->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list;
    }

    /**
     * return array
     */
    private static function bidStatusAsMapCommon()
    {
        $models = self::find()->where(['admin_name' => null])->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list;
    }

    /**
     * return array
     */
    public static function bidStatusAsMapForWorkshop()
    {
        $models = self::find()->where(['admin_name' => self::WORKSHOP_STATUSES])->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list + self::bidStatusAsMapCommon();
    }

    /**
     * return array
     */
    public static function bidStatusAsMapForAgency()
    {
        $models = self::find()->where(['admin_name' => self::AGENCY_STATUSES])->orderBy('name')->all();
        $list = ArrayHelper::map($models, 'id', 'name');

        return $list + self::bidStatusAsMapCommon();
    }

    public static function getAdminStatusId($adminName)
    {
        $model = self::find()->where(['admin_name' => $adminName])->one();

        return $model ? $model->id : null;
    }
}
