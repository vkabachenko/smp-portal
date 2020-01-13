<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "news_like".
 *
 * @property int $id
 * @property string $status
 * @property int $user_id
 * @property int $news_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property News $news
 * @property User $user
 */
class NewsLike extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news_like';
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
            [['status', 'user_id', 'news_id'], 'required'],
            [['status'], 'string'],
            [['user_id', 'news_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['news_id'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['news_id' => 'id']],
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
            'status' => 'Status',
            'user_id' => 'User ID',
            'news_id' => 'News ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function countLikes($newsId, $status)
    {
        return self::find()
            ->where(['news_id' => $newsId, 'status' => $status])
            ->count();
    }

    public static function isUserLike($newsId, $userId, $status)
    {
        return self::find()
            ->where(['user_id' => $userId, 'news_id' => $newsId, 'status' => $status])
            ->exists();
    }

}
