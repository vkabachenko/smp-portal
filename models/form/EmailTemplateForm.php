<?php


namespace app\models\form;


use app\models\TemplateModel;
use yii\base\Model;

class EmailTemplateForm extends Model
{
    public $content;
    public $signature;

    public function rules()
    {
        return [
            [['content', 'signature'], 'string'],
            [['content', 'signature'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'content' => 'Содержание',
            'signature' => 'Подпись',
        ];
    }

    public function fillFieldsFromDb()
    {
        $model = TemplateModel::findByName(TemplateModel::EMAIL_ACT);
        if (!is_null($model)) {
            $fields = $model->fields;
            $this->content = isset($fields['content']) ? $fields['content'] : null;
            $this->signature = isset($fields['signature']) ? $fields['signature'] : null;
        }
    }

    public function saveFieldsToDb()
    {
        $fields = ['content' => $this->content, 'signature' => $this->signature];
        $model = TemplateModel::findByName(TemplateModel::EMAIL_ACT);
        if (is_null($model)) {
            $model = new TemplateModel(['name' => TemplateModel::EMAIL_ACT]);
        }
        $model->fields = $fields;
        if (!$model->save()) {
            \Yii::error($model->getErrors());
            return false;
        } else {
            return true;
        }
    }
}