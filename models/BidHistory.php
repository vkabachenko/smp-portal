<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bid_history".
 *
 * @property int $id
 * @property int $bid_id
 * @property int $user_id
 * @property int $status
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Bid $bid
 * @property User $user
 */
class BidHistory extends \yii\db\ActiveRecord
{
    const STATUS_CREATED = 'created';
    const STATUS_FILLED = 'filled';
    const STATUS_SENT = 'sent';
    const STATUS_CLARIICATION_NEEDED = 'clarification needed';
    const STATUS_APPROVED = 'approved';
    const STATUS_DONE = 'done';
    const STATUS_ISSUED = 'issued';
    const STATUS_PAYED = 'payed';
    const STATUS_CLOSED = 'closed';

    const STATUSES = [
        self::STATUS_CREATED => 'Создана',
        self::STATUS_FILLED => 'Заполнена',
        self::STATUS_SENT => 'Отправлена',
        self::STATUS_APPROVED => 'Одобрена',
        self::STATUS_CLARIICATION_NEEDED => 'Требует уточнения',
        self::STATUS_DONE => 'Выполнена',
        self::STATUS_ISSUED => 'Выдана',
        self::STATUS_PAYED => 'Оплачена',
        self::STATUS_CLOSED => 'Закрыта',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid_history';
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
            [['bid_id'], 'required'],
            [['bid_id', 'user_id'], 'integer'],
            [['comment'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['bid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bid::className(), 'targetAttribute' => ['bid_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ['status', 'in', 'range' => array_keys(self::STATUSES)],
            ['status', 'default', 'value' => self::STATUS_CREATED],
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
            'user_id' => 'User ID',
            'status' => 'Status',
            'comment' => 'Comment',
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
