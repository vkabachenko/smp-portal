<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bid_job".
 *
 * @property int $id
 * @property int $bid_id
 * @property int $jobs_catalog_id
 * @property double $price
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Bid $bid
 * @property JobsCatalog $jobsCatalog
 */
class BidJob extends \yii\db\ActiveRecord implements TranslatableInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid_job';
    }

    public static function translateName()
    {
        return 'Работы';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => function () {
                    return date('Y-m-d H:i:s');
                }
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid_id', 'jobs_catalog_id'], 'required'],
            [['bid_id', 'jobs_catalog_id'], 'integer'],
            [['price'], 'number'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['bid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bid::className(), 'targetAttribute' => ['bid_id' => 'id']],
            [['jobs_catalog_id'], 'exist', 'skipOnError' => true, 'targetClass' => JobsCatalog::className(), 'targetAttribute' => ['jobs_catalog_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bid_id' => 'Bid ID',
            'jobs_catalog_id' => 'Вид работы',
            'price' => 'Стоимость',
            'description' => 'Описание',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBid()
    {
        return $this->hasOne(Bid::className(), ['id' => 'bid_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobsCatalog()
    {
        return $this->hasOne(JobsCatalog::className(), ['id' => 'jobs_catalog_id']);
    }
}
