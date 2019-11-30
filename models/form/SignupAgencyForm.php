<?php

namespace app\models\form;

use app\models\Agency;
use app\models\LoginForm;
use app\models\Manager;
use app\models\Manufacturer;
use yii\base\Model;
use app\models\User;

class SignupAgencyForm extends Model
{
    public $userName;
    public $agencyName;
    public $manufacturerId;
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
            [['email', 'agencyName', 'manufacturerId', 'userName'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['agencyName', 'userName'], 'string', 'max' => 255],
            [['manufacturerId'], 'integer'],
            [['manufacturerId'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturer::className(), 'targetAttribute' => ['manufacturerId' => 'id']],
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
            'manufacturerId' => 'Производитель',
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
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if (!$user->save()) {
            \Yii::error($user->getErrors());
            $transaction->rollBack();
            return false;
        }

        $agency = new Agency([
            'name' => $this->agencyName,
            'manufacturer_id' => $this->manufacturerId
        ]);
        if (!$agency->save()) {
            \Yii::error($agency->getErrors());
            $transaction->rollBack();
            return false;
        }
        
        $manager = new Manager([
            'user_id' => $user->id,
            'agency_id' => $agency->id,
            'main' => true
        ]);
        if (!$manager->save()) {
            \Yii::error($agency->getErrors());
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();

        \Yii::$app->user->login($user, 0);
        LoginForm::assignRole();
        
        return true;
    }

}