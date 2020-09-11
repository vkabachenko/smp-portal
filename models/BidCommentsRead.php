<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bid_comments_read".
 *
 * @property int $id
 * @property int $bid_id
 * @property int $user_id
 * @property string $date_read
 *
 * @property Bid $bid
 * @property User $user
 */
class BidCommentsRead extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid_comments_read';
    }

    public static function createOrUpdate($bidId, $userId = null, $dateRead = null)
    {
        if (is_null($dateRead)) {
            $dateRead = date("Y-m-d H:i:s");
        }

        if (is_null($userId)) {
            $userId = \Yii::$app->user->id;
        }

        $model = self::find()->where(['bid_id' => $bidId, 'user_id' => $userId])->one();

        if (is_null($model)) {
            $model = new self(['bid_id' => $bidId, 'user_id' => $userId]);
        }

        $model->date_read = $dateRead;

        if (!$model->save()) {
            \Yii::error($model->errors);
        }
    }

    public static function isExistUnread($bidId, $userId = null)
    {
        if (is_null($userId)) {
            $userId = \Yii::$app->user->id;
        }

        /* @var $model \app\models\BidCommentsRead */
        $model = self::find()->where(['bid_id' => $bidId, 'user_id' => $userId])->one();

        $lastDateRead = $model ? $model->date_read : '';

        $user = User::findOne($userId);

        $query = BidComment::find()
            ->where(['bid_id' => $bidId])
            ->andWhere(['>', 'updated_at', $lastDateRead]);

        if (!\Yii::$app->user->can('managePrivateComments', ['bidId' => $bidId])) {
            $query->andWhere(['private' => false]);
        }

        return $query->exists();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bid_id', 'user_id', 'date_read'], 'required'],
            [['bid_id', 'user_id'], 'integer'],
            [['date_read'], 'safe'],
            [['bid_id', 'user_id'], 'unique', 'targetAttribute' => ['bid_id', 'user_id']],
            [['bid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bid::className(), 'targetAttribute' => ['bid_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'date_read' => 'Date Read',
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
