<?php

namespace app\models\form;

use app\models\Agency;
use app\models\Manager;
use app\models\Manufacturer;
use yii\base\Model;
use app\models\User;

class SignupAgencyForm extends Model
{
    /* @var Manager */
    public $manager;

    public $userName;
    public $agencyName;
    public $email;
    public $password;
    public $verifyCode;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'agencyName'], 'trim'],
            [['email', 'agencyName', 'userName'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['agencyName', 'userName'], 'string', 'max' => 255],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'agencyName' => 'Название представительства',
            'userName' => 'ФИО главного менеджера',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'verifyCode' => 'Проверочный код с картинки'
        ];
    }
    
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();

        $user = new User();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->name = $this->userName;
        $user->role = 'manager';
        $user->status = User::STATUS_INACTIVE;
        $user->verification_token = \Yii::$app->security->generateRandomString(16);
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if (!$user->save()) {
            \Yii::error($user->getErrors());
            $transaction->rollBack();
            return false;
        }

        $agency = new Agency([
            'name' => $this->agencyName,
            'manufacturer_id' => Manufacturer::find()->one()->id
        ]);
        if (!$agency->save()) {
            \Yii::error($agency->getErrors());
            $transaction->rollBack();
            return false;
        }
        
        $this->manager = new Manager([
            'user_id' => $user->id,
            'agency_id' => $agency->id,
            'main' => true
        ]);
        if (!$this->manager->save()) {
            \Yii::error($this->manager->getErrors());
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        
        return true;
    }

}