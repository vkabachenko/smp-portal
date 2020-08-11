<?php
namespace app\services\mail;

use app\models\Manager;

class InviteAgency implements SendMail
{
    /**
     * @var \app\models\Manager
     */
    private $manager;

    /* @var bool */
    private $is_independent;

    public function __construct(Manager $manager, $is_independent)
    {
        $this->manager = $manager;
        $this->is_independent = $is_independent;
    }

    public function send()
    {
        try {
            $result = \Yii::$app->mailer
                ->compose(
                    [
                        'html' => 'invite-agency/html'
                    ],
                    [
                        'manager' => $this->manager,
                        'is_independent' => $this->is_independent
                    ]
                )
                ->setFrom(\Yii::$app->params['adminEmail'])
                ->setTo(
                    \Yii::$app->params['adminEmail']
                )
                ->setSubject('Подтвердите регистрацию представительства ' . $this->manager->agency->name)
                ->send();
        } catch (\Exception $exc) {
            $result = false;
            \Yii::error($exc->getMessage());
        }
        return $result;
    }
}