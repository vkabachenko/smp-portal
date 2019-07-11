<?php

namespace app\models;

use app\models\form\CommentForm;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bid_comment".
 *
 * @property int $id
 * @property int $bid_id
 * @property int $private
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 * @property string $user_id
 *
 * @property Bid $bid
 */
class BidComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid_comment';
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
            [['bid_id', 'comment'], 'required'],
            [['bid_id', 'private', 'user_id'], 'integer'],
            [['comment'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['bid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bid::className(), 'targetAttribute' => ['bid_id' => 'id']],
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
            'private' => 'Private',
            'comment' => 'Комментарий',
            'created_at' => 'Создано',
            'updated_at' => 'Updated At',
            'user_id' => 'Написал'
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

    public static function createFromForm(CommentForm $commentForm, $bidId, $userId) {
        if ($commentForm->comment) {
            $model = new self([
                'bid_id' => $bidId,
                'user_id' => $userId,
                'comment' => $commentForm->comment
            ]);
            if (!$model->save()) {
                \Yii::error($model->getErrors());
            }
        }
    }
}
