<?php
/**
 * Created by PhpStorm.
 * User: allovince
 * Date: 15/1/30
 * Time: 上午11:38
 */

namespace Eva\EvaSms\Message;

interface MessageInterface
{
    const TYPE_STANDARD = 'STANDARD_MESSAGE';

    const TYPE_TEMPLATE = 'TEMPLATE_MESSAGE';

    public function setRecipient($recipient);

    public function getRecipient();

    public function __toString();
}
