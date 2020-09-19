<?php


namespace app\services\mail;


use app\models\User;

class Feedback implements SendMail
{
    private $subject;
    private $message;

    /* @var User */
    private $user;

    /* @var array */
    private $dirs;

    public function __construct($subject, $message, $dirs, User $user)
    {
        $this->subject = $subject;
        $this->user = $user;
        $this->dirs = $dirs;
        $this->message = $this->createTextMessage($message);
    }

    private function createTextMessage($message)
    {
        $details = 'Тема: ' . $this->subject . "\n"
            . 'Email пользователя: ' . $this->user->email;

        if ($this->user->master) {
            $office = "\n\n" .'Мастерская ' . $this->user->master->workshop->name;
        } elseif ($this->user->manager) {
            $office = "\n\n" .'Представительство ' . $this->user->manager->agency->name;
        } else {
            $office = '';
        }

        return $message . "\n\n" . $details . $office;
    }

    public function send()
    {
        try {
            $message = \Yii::$app->mailer->compose();

            $message
                ->setTextBody($this->message)
                ->setFrom(\Yii::$app->params['adminEmail'])
                ->setTo(
                    \Yii::$app->params['adminEmail']
                )
                ->setSubject('Обратная связь от пользователя');

            foreach ($this->dirs as $dir) {
                $path = \Yii::$app->getModule('filepond')->basePath . $dir;
                $files = array_diff(scandir($path), ['..', '.']);
                $message->attach($path . '/' . reset($files));
            }

            $result = $message->send();
        } catch (\Exception $exc) {
            $result = false;
            \Yii::error($exc->getMessage());
        }
        return $result;
    }


}