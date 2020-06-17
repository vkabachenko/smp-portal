<?php


namespace app\services\mail;


class Feedback implements SendMail
{
    private $subject;
    private $message;
    private $userEmail;

    /* @var array */
    private $dirs;

    public function __construct($subject, $message, $dirs, $userEmail)
    {
        $this->subject = $subject;
        $this->userEmail = $userEmail;
        $this->dirs = $dirs;
        $this->message = $this->createTextMessage($message);
    }

    private function createTextMessage($message)
    {
        $details = 'Темв: ' . $this->subject . "\n"
            . 'Email пользователя: ' . $this->userEmail;

        return $message . "\n\n" . $details;
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