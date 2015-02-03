<?php
/**
 * Created by PhpStorm.
 * User: allovince
 * Date: 15/1/30
 * Time: 上午11:38
 */

namespace Eva\EvaSms\Message;

/**
 * Interface MessageInterface
 * @package Eva\EvaSms\Message
 */
interface MessageInterface
{
    /**
     * Message Type: Standard
     */
    const TYPE_STANDARD = 'STANDARD_MESSAGE';

    /**
     * Message Type: Template
     */
    const TYPE_TEMPLATE = 'TEMPLATE_MESSAGE';

    /**
     * @param $recipient
     * @return mixed
     */
    public function setRecipient($recipient);

    /**
     * @return mixed
     */
    public function getRecipient();

    /**
     * @return string
     */
    public function __toString();
}
