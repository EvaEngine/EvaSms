<?php
/**
 * Created by PhpStorm.
 * User: allovince
 * Date: 15/1/30
 * Time: 下午3:22
 */

namespace Eva\EvaSms\Result;

use Eva\EvaSms\Message\MessageInterface;
use Guzzle\Http\Message\Response;

class StandardResult implements ResultInterface
{
    protected $status = ResultInterface::STATUS_QUEUED;

    protected $message;

    protected $rawResponse;

    protected $sentTimestamp;

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $supportedStatus = array(
            ResultInterface::STATUS_DELIVERED,
            ResultInterface::STATUS_FAILED,
            ResultInterface::STATUS_QUEUED,
            ResultInterface::STATUS_SENT,
        );

        if (in_array($status, $supportedStatus)) {
            $this->status = $status;
        }
        return $this;
    }

    public function setMessage(MessageInterface $message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setRawResponse(Response $response)
    {
        $this->rawResponse = $response;
        return $this;
    }

    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    public function getSentTimestamp()
    {
        return $this->sentTimestamp;
    }

    public function setSentTimestamp($timestamp)
    {
        $this->sentTimestamp = (int) $timestamp;
        return $this;
    }

    public function __construct(MessageInterface $message, Response $rawResponse, $timestamp = null)
    {
        $this->setMessage($message);
        $this->setRawResponse($rawResponse);
        $this->sentTimestamp = $timestamp ?: time();
    }


    public function __toString()
    {
        $result = array(
            'message' => (string) $this->getMessage(),
            'sentTime' => $this->sentTimestamp,
            'result' => (string) $this->getRawResponse(),
        );

        return json_encode($result);
    }
}
