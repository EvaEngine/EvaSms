<?php
/**
 * Created by PhpStorm.
 * User: allovince
 * Date: 15/1/30
 * Time: 下午2:32
 */

namespace Eva\EvaSms\Result;

use Eva\EvaSms\Message\MessageInterface;
use Guzzle\Http\Message\Response;

interface ResultInterface
{
    /**
     * The message was received by the provider, and sent to the mobile phone.
     */
    const STATUS_SENT = 'sent';

    /**
     * The message was sent by the provider and delivered to the mobile phone.
     */
    const STATUS_DELIVERED = 'delivered';

    /**
     * The provider failed to send the message.
     */
    const STATUS_FAILED = 'failed';

    /**
     * The message is not sent yet.
     */
    const STATUS_QUEUED = 'queued';

    public function getSentTimestamp();

    public function setSentTimestamp($timestamp);

    public function getStatus();

    public function setStatus($status);

    public function setMessage(MessageInterface $message);

    public function getMessage();

    public function setRawResponse(Response $response);

    public function getRawResponse();

    public function __toString();
}

