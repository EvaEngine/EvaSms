<?php
/**
 * Created by PhpStorm.
 * User: allovince
 * Date: 15/1/30
 * Time: 上午11:34
 */

namespace Eva\EvaSms\Providers;

use Eva\EvaSms\Exception\InvalidNumberException;
use Eva\EvaSms\Exception\UnsupportedCountryException;
use Eva\EvaSms\Exception\UnsupportedException;
use Eva\EvaSms\Message\MessageInterface;
use Eva\EvaSms\Message\StandardMessage;
use Eva\EvaSms\Message\TemplateMessage;
use Eva\EvaSms\Result\StandResult;
use Eva\EvaSms\Sender;
use Guzzle\Http\Client;

class Submail implements ProviderInterface
{
    const API_URL = 'https://api.submail.cn/message/xsend.json';

    protected $appid;

    protected $appkey;

    protected $signature;

    public function sendStandardMessage(StandardMessage $message)
    {
        throw new UnsupportedException(sprintf('Starndard message not supported by provider %s', 'submail'));
    }


    /**
     * @param TemplateMessage $message
     * @return StandResult
     */
    public function sendTemplateMessage(TemplateMessage $message)
    {
        $number = $message->getRecipient();
        if (!$this->isNumberValid($number)) {
            throw new InvalidNumberException(sprintf('Mobile number %s not valid by provider %s', $number, 'submail'));
        }

        if (!$this->isCountrySupported($number)) {
            throw new UnsupportedException(sprintf('Mobile number %s not supported by provider %s', $number,
                'submail'));
        }

        //Raw auth by appkey
        $params = array(
            'appid' => $this->appid,
            'to' => $number,
            'project' => $message->getTemplateId(),
            'vars' => json_encode($message->getVars()),
            'signature' => $this->appkey,
        );

        /*
        $params = array(
            'appid' => $this->appid,
            'to' => '18501760817',
            'project' => $message->getTemplateId(),
            'vars' => json_encode($message->getVars()),
            'timestamp' => time(),
            'sige_type' => 'md5',
        );
        $signature = $this->getSignature($params);
        $params['signature'] = $signature;
        */

        $client = Sender::getHttpClient();
        $response = $client->post(self::API_URL, [], $params)->send();
        return new StandResult();
    }

    protected function getSignature($params)
    {
        ksort($params);
        reset($params);
        $signature = array();
        foreach ($params as $key => $value) {
            $signature[] = $key . '=' . $value;
        }
        $signature = implode('&', $signature);
        var_dump($signature);
        return md5($this->appid . $this->appkey . $signature . $this->appid . $this->appkey);
    }

    public function isNumberValid($number)
    {
        return true;
    }

    public function isCountrySupported($number)
    {
        if (substr($number, 0, 3) !== '+86') {
            return false;
        }
        return true;
    }

    public function __construct($appid, $appkey)
    {
        $this->appid = $appid;
        $this->appkey = $appkey;
    }
}

