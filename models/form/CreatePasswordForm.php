<?php


namespace app\models\form;

use yii\base\Model;

class CreatePasswordForm extends Model
{
    public $password;
    public $passwordAgain;

    public function rules()
    {
        return [
            [['password', 'passwordAgain'], 'required'],
            [['password', 'passwordAgain'], 'string', 'length' => [6, 255]],
            ['password', 'compare', 'compareAttribute' => 'passwordAgain'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Введите пароль',
            'passwordAgain' => 'Введите пароль еще раз',
        ];
    }

}