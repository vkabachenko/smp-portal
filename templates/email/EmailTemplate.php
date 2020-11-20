<?php


namespace app\templates\email;

use app\helpers\common\CryptHelper;
use app\models\DecisionStatusInterface;
use app\models\TemplateModel;

class EmailTemplate
{
    /**
     * @var TemplateModel
     */
    private $template;
    /**
     * @var DecisionStatusInterface
     */
    private $decision;

    public function __construct($template, DecisionStatusInterface $decision)
    {
        $this->template = $template;
        $this->decision = $decision;
    }

    public function getSubject($bidId = null)
    {
        if ($this->decision->email_subject) {
            $subj = $this->getText(strval($this->decision->email_subject));
        } else {
            $subj =  $this->template ? $this->getText(strval($this->template->email_subject)) : '';
        }

        if ($bidId) {
            $subj .= ' id=' . CryptHelper::numhash($bidId);
        }

        return $subj;
    }

    public function getMailContent($content = '')
    {
        if ($this->decision->email_body) {
            $body = $this->getText(strval($this->decision->email_body));
            $signature = $this->getText(strval($this->decision->email_signature));
        } else {
            $body = $this->getText(strval($this->template ? $this->template->email_body: ''));
            $signature = $this->getText(strval($this->template ? $this->template->email_signature : ''));
        }
        return sprintf("%s\n%s\n-------\n%s", $body, $content, $signature);
    }


    protected function getText($text)
    {
        return str_replace(array_keys($this->getParams()), array_values($this->getParams()), $text);
    }

    protected function getParams()
    {
        return [];
    }

}