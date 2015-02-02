<?php
/**
 * Created by PhpStorm.
 * User: allovince
 * Date: 15/1/30
 * Time: 下午12:43
 */

namespace Eva\EvaSms\Message;

class StandardMessage implements MessageInterface
{
    protected $recipient;

    protected $body;

    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function __construct($recipient, $body)
    {
        $this->recipient = $recipient;
        $this->body = $body;
    }

    public function __toString()
    {
        return json_encode(array(
            'recipient' => $this->recipient,
            'body' => $this->body,
        ));
    }
}