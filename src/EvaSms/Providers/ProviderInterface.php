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
use Eva\EvaSms\Result\ResultInterface;
use Guzzle\Http\Client;

/**
 * Interface ProviderInterface
 * @package Eva\EvaSms\Providers
 */
interface ProviderInterface
{
    /**
     * @param TemplateMessage $message
     * @return mixed
     */
    public function sendTemplateMessage(TemplateMessage $message);

    /**
     * @param StandardMessage $message
     * @return ResultInterface
     */
    public function sendStandardMessage(StandardMessage $message);

    /**
     * @param $number
     * @return ResultInterface
     */
    public function isNumberValid($number);

    /**
     * @param $number
     * @return bool
     */
    public function isCountrySupported($number);

}

