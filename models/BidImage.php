<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "bid_image".
 *
 * @property int $id
 * @property int $bid_id
 * @property string $file_name
 * @property string $src_name
 * @property string $created_at
 * @property string $updated_at
 * @property string $user_id
 * @property bool $sent
 *
 * @property Bid $bid
 * @property User $user
 */
class BidImage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid_image';
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
            [['bid_id', 'file_name', 'src_name'], 'required'],
            [['bid_id', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['file_name', 'src_name'], 'string', 'max' => 255],
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
            'file_name' => 'File Name',
            'src_name' => 'Src Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User id'
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

    public function getPath()
    {
        return \Yii::getAlias('@webroot/uploads/') . $this->src_name;
    }

    public function getAbsoluteUrl()
    {
        return Url::to('@web/uploads/' . $this->src_name, true);
    }

    /**
     * @param $bidId int
     * @param $user  \yii\web\User
     * @return array
     */
    public static function getAllowedImages($bidId, \yii\web\User $user)
    {
        if ($user->can('manager')) {
            return [];
        }

        $images = self::find()->where(['bid_id' => $bidId])->all();
        $images = array_filter($images, function(BidImage $image) use ($user) {
            return $user->can('viewImage', ['imageId' => $image->id]);
        });

        return $images;
    }
}
