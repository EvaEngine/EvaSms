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
use GuzzleHttp\Client;

/**
 * Class Sender
 * @package Eva\EvaSms
 */
class Sender
{

    /**
     * @var Client
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
    }

    /**
     * @param $countryCode
     */
    public static function setDefaultCountryCode($countryCode)
    {
        self::$defaultCountryCode = $countryCode;

    }

    /**
     * @return Client
     */
    public static function getHttpClient()
    {
        if (self::$httpClient) {
            return self::$httpClient;
        }

        $client = new Client([
            'timeout' => self::$defaultTimeout
        ]);
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
        $provider = $this->getProvider($mobileNumber);
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
        $provider = $this->getProvider($mobileNumber);
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
    public function getProvider($mobileNumber)
    {
        $config = $this->getDI()->getConfig();
        $adapterMapping = array(
            'submail' => 'Eva\EvaSms\Providers\Submail',
            'submailIntl' => 'Eva\EvaSms\Providers\SubmailIntlAdapter',
        );
        if (substr($mobileNumber, 0, 3) === '+86') {
            $adapterKey = 'Submail';
        } else {
            $adapterKey = 'SubmailIntlAdapter';
        }
        $adapterKey = false === strpos($adapterKey, '\\') ? strtolower($adapterKey) : $adapterKey;
        $adapterClass = empty($adapterMapping[$adapterKey]) ? $adapterKey : $adapterMapping[$adapterKey];
        if (false === class_exists($adapterClass)) {
            throw new Exception\RuntimeException(sprintf('No sms provider found by %s', $adapterClass));
        }
        $provider = new $adapterClass($config->smsSender->appid, $config->smsSender->appkey);
        return $provider;
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
