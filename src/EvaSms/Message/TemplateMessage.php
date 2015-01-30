<?php
/**
 * Created by PhpStorm.
 * User: allovince
 * Date: 15/1/30
 * Time: 下午12:43
 */

namespace Eva\EvaSms\Message;

class TemplateMessage implements MessageInterface
{
    protected $recipient;

    protected $templateId;

    protected $vars = array();

    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
        return $this;
    }

    public function getTemplateId()
    {
        return $this->templateId;
    }

    public function setVars($vars)
    {
        $this->vars = $vars;
        return $this;
    }

    public function getVars()
    {
        return $this->vars;
    }


    public function __construct($recipient, $templateId, $vars = array())
    {
        $this->recipient = $recipient;
        $this->templateId = $templateId;
        $this->vars = $vars;
    }
}