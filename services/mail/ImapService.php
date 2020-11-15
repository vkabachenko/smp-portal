<?php


namespace app\services\mail;

use app\models\Agency;
use app\models\Bid;
use app\models\BidHistory;
use app\models\BidStatus;
use app\models\Workshop;
use PhpImap\IncomingMail;
use PhpImap\Mailbox;
use yii\db\ActiveRecord;

class ImapService
{
    /* @var $mailEngine Mailbox */
    private $mailEngine;

    /* @var Workshop */
    private $workshop;

    public function __construct($email)
    {
        $this->workshop = Workshop::find()->where(['email3' => $email]);
        if ($this->workshop && $this->workshop->imap_server && $this->workshop->mailbox_pass) {
            $this->mailEngine = new Mailbox($this->workshop->imap_server, $email, $this->workshop->mailbox_pass);
        } else {
            $this->mailEngine = null;
        }

    }

    /**
     * @param $sinceDate
     * @return IncomingMail[]
     */
    public function getMails($sinceDate)
    {
        if (!$this->mailEngine) {
            return [];
        }

        $mailsIds = $this->mailEngine->searchMailbox('SINCE ' . $sinceDate);
        if (!$mailsIds) {
            return [];
        }

        $mailsList = [];
        foreach ($mailsIds as $mailId) {
            $mailsList[] = $this->mailEngine->getMail($mailId);
        }
        return $mailsList;
    }

    public function processMail(IncomingMail $mail)
    {
        \Yii::info('email subject' .  $mail->subject);

        $bid = $this->getBid(strval($mail->subject));

        if (!$bid
            || ($bid->status_id != BidStatus::getId(BidStatus::STATUS_SENT_WORKSHOP)
            && $bid->status_id != BidStatus::getId(BidStatus::STATUS_READ_AGENCY))
        ) {
            return;
        }

        \Yii::info('bid found' .  $bid->id);

        \Yii::info('from address' .  $mail->fromAddress);

        $agency = $this->getAgency(strval($mail->fromAddress));

        if (!$agency || $bid->agency_id != $agency->id) {
            return;
        }

        \Yii::info('agency found' .  $agency->id);

        $bid->setStatus(BidStatus::STATUS_SENT_AGENCY);
        BidHistory::createRecord([
            'bid_id' => $bid->id,
            'user_id' => null,
            'action' => 'Изменен статус заявки из письма представительства: ' . BidStatus::STATUS_SENT_AGENCY
        ]);

    }

    /**
     * @param $subject
     * @return Bid|ActiveRecord|null
     */
    private function getBid($subject)
    {
        preg_match('/\[ном] (\d+)/', $subject, $matches);
        $bidNumber = isset($matches[1]) ? $matches[1] : null;

        preg_match('/\[ном1C] (\d+)/', $subject, $matches);
        $bid1CNumber = isset($matches[1]) ? $matches[1] : null;

        $bid = null;

        if ($bidNumber) {
            $bid = Bid::find()->where(['bid_number' => $bidNumber])->one();
        }

        if (!$bid && $bid1CNumber) {
            $bid = Bid::find()->where(['bid_1C_number' => $bid1CNumber])->one();
        }

        return $bid;
    }

    /**
     * @param $from
     * @return Agency|ActiveRecord|null
     */
    private function getAgency($from)
    {
        $agency = Agency::find()
            ->where(['email2' => $from])
            ->orWhere(['email4' => $from])
            ->one();

        return $agency;
    }

}