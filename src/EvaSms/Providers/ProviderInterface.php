<?php
/**
 * Created by PhpStorm.
 * User: allovince
 * Date: 15/1/30
 * Time: 上午11:14
 */

namespace Eva\EvaSms\Providers;

use Eva\EvaSms\Message\MessageInterface;
use Eva\EvaSms\Message\StandardMessage;
use Eva\EvaSms\Message\TemplateMessage;
use Guzzle\Http\Client;

interface ProviderInterface
{
    public function sendTemplateMessage(TemplateMessage $message);

    public function sendStandardMessage(StandardMessage $message);

    public function isNumberValid($number);

    public function isCountrySupported($number);

}


