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
    const STANDARD_MESSAGE = 'STANDARD_MESSAGE';

    const TEMPLATE_MESSAGE = 'TEMPLATE_MESSAGE';

    public function setRecipient($recipient);

    public function getRecipient();
}
