<?php
namespace app\services\mail;

use app\models\Manager;
use yii\helpers\Html;

class InviteManager implements SendMail
{
    /**
     * @var \app\models\Manager
     */
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function send()
    {
        try {
            $result = \Yii::$app->mailer
                ->compose(
                    [
                        'html' => 'invite-manager/html'
                    ],
                    [
                        'manager' => $this->manager,
                    ]
                )
                ->setFrom(\Yii::$app->params['adminEmail'])
                ->setTo(
                    $this->manager->user->email
                )
                ->setSubject('Приглашение в представительство ' . $this->manager->agency->name)
                ->send();
        } catch (\Exception $exc) {
            $result = false;
            \Yii::error($exc->getMessage());
        }
        return $result;
    }
}