<?php


namespace app\models\form;

use yii\base\Model;
use app\models\User;

class ResetPasswordRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            [['email'], 'trim'],
            [['email'], 'required'],
            ['email', 'email'],
            [['email'], 'string', 'max' => 255],
            ['email', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['email' => 'email']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
        ];
    }

    public function getUser()
    {
        $user = User::findByUsername($this->email);
        if (is_null($user)) {
            throw new \DomainException('User not found');
        }
        $user->generatePasswordResetToken();
        if (!$user->save()) {
            \Yii::error($user->getErrors());
            throw new \DomainException('Fail saving user');
        }
        return $user;
    }

}