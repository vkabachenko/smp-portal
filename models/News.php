<?php

namespace app\models;

use app\models\query\NewsQuery;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $title
 * @property string $content
 * @property string $target
 * @property boolean $active
 */
class News extends \yii\db\ActiveRecord
{
    const TARGETS = [
        'all' => 'Портал',
        'agencies' => 'Представительства',
        'workshops' => 'Мастерские'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    public static function find()
    {
        return new NewsQuery(get_called_class());
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
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'content', 'target'], 'required'],
            [['content'], 'string'],
            [['title', 'target'], 'string', 'max' => 255],
            [['active'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'created_at' => 'Создана',
            'updated_at' => 'Изменена',
            'title' => 'Заголовок',
            'content' => 'Содержание',
            'active' => 'Опубликована',
            'target' => 'Публиковать для',
        ];
    }

    public static function getPublishedNews($target, $limit = null)
    {
        $targetNews = self::find()
            ->published()
            ->andWhere(['target' => $target])
            ->orderBy('updated_at DESC')
            ->limit($limit)
            ->all();
        $allNews = self::find()
            ->published()
            ->andWhere(['target' => 'all'])
            ->orderBy('updated_at DESC')
            ->limit($limit)
            ->all();

        return array_merge($targetNews, $allNews);
    }
}
