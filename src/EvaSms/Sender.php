<?php
/**
 * Created by PhpStorm.
 * User: allovince
 * Date: 15/1/30
 * Time: 上午11:10
 */

namespace Eva\EvaSms;

use Eva\EvaSms\Exception\InvalidNumberException;
use Eva\EvaSms\Message\StandardMessage;
use Eva\EvaSms\Message\TemplateMessage;
use Eva\EvaSms\Providers\ProviderInterface;
use GuzzleHttp\Client as HttpClient;

/**
 * Class Sender
 * @package Eva\EvaSms
 */
class Sender
{

    /**
     * @var HttpClient
     */
    protected static $httpClient;

    /**
     * @var float
     */
    protected static $defaultTimeout = 1;

    /**
     * @var string
     */
    protected static $defaultCountryCode = '+86';

    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @param $timeout
     */
    public static function setDefaultTimeout($timeout)
    {
        self::$defaultTimeout = $timeout;

        if (self::$httpClient) {
            self::$httpClient->setDefaultOption('timeout', $timeout);
        }
    }

    /**
     * @param $countryCode
     */
    public static function setDefaultCountryCode($countryCode)
    {
        self::$defaultCountryCode = $countryCode;

    }

    /**
     * @return HttpClient
     */
    public static function getHttpClient()
    {
        if (self::$httpClient) {
            return self::$httpClient;
        }

        $client = new HttpClient();
        $client->setDefaultOption('timeout', self::$defaultTimeout);
        return self::$httpClient = $client;
    }

    /**
     * Valid full mobile number like +861234567890
     * @param $mobileNumber
     * @return bool
     */
    public static function isMobileNumberValid($mobileNumber)
    {
        if ($mobileNumber{0} !== '+' || strlen($mobileNumber) < 12 || !is_numeric(ltrim($mobileNumber, '+'))) {
            return false;
        }
        return true;
    }

    /**
     * @param $mobileNumber
     * @param $templateId
     * @param array $vars
     * @return mixed
     */
    public function sendTemplateMessage($mobileNumber, $templateId, array $vars = array())
    {
        $provider = $this->getProvider();
        if (!$provider) {
            throw new \RuntimeException('No provider found');
        }

        $mobileNumber = $mobileNumber{0} === '+' ? $mobileNumber : self::$defaultCountryCode . $mobileNumber;

        if (!self::isMobileNumberValid($mobileNumber)) {
            throw new InvalidNumberException(sprintf("Mobile number %s invalid", $mobileNumber));
        }

        $message = new TemplateMessage($mobileNumber, $templateId, $vars);

        return $provider->sendTemplateMessage($message);
    }

    /**
     * @param $mobileNumber
     * @param $messageBody
     * @return Result\ResultInterface
     */
    public function sendStandardMessage($mobileNumber, $messageBody)
    {
        $provider = $this->getProvider();
        if (!$provider) {
            throw new \RuntimeException('No provider found');
        }

        $mobileNumber = $mobileNumber{0} === '+' ? $mobileNumber : self::$defaultCountryCode . $mobileNumber;

        if (!self::isMobileNumberValid($mobileNumber)) {
            throw new InvalidNumberException(sprintf("Mobile number %s invalid", $mobileNumber));
        }

        $message = new StandardMessage($mobileNumber, $messageBody);

        return $provider->sendStandardMessage($message);
    }

    /**
     * @return ProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param ProviderInterface $provider
     * @return $this
     */
    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;
        return $this;
    }

}

