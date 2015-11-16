<?php
/**
 * Created by PhpStorm.
 * User: allovince
 * Date: 15/1/30
 * Time: 上午11:34
 */

namespace Eva\EvaSms\Providers;

use Eva\EvaSms\Exception\InvalidNumberException;
use Eva\EvaSms\Exception\UnsupportedException;
use Eva\EvaSms\Message\StandardMessage;
use Eva\EvaSms\Message\TemplateMessage;
use Eva\EvaSms\Result\ResultInterface;
use Eva\EvaSms\Result\StandardResult;
use Eva\EvaSms\Sender;

/**
 * Class Submail
 * @package Eva\EvaSms\Providers
 */
class Submail implements ProviderInterface
{
    /**
     * SMS sending API
     */
    const API_URL = 'https://api.submail.cn/message/xsend.json';

    /**
     * @var string
     */
    protected $appid;

    /**
     * @var string
     */
    protected $appkey;

    /**
     * @var string
     */
    protected $signature;

    /**
     * Submail not support stardard message
     *
     * @param StandardMessage $message
     * @return mixed|void
     */
    public function sendStandardMessage(StandardMessage $message)
    {
        throw new UnsupportedException(sprintf('Starndard message not supported by provider %s', 'submail'));
    }


    /**
     * @param TemplateMessage $message
     * @return StandardResult
     */
    public function sendTemplateMessage(TemplateMessage $message)
    {
        $number = $message->getRecipient();
        if (!$this->isNumberValid($number)) {
            throw new InvalidNumberException(sprintf('Mobile number %s not valid by provider %s', $number, 'submail'));
        }

        if (!$this->isCountrySupported($number)) {
            throw new UnsupportedException(sprintf(
                'Mobile number %s not supported by provider %s',
                $number,
                'submail'
            ));
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
            'to' => $number,
            'project' => $message->getTemplateId(),
            'vars' => json_encode($message->getVars()),
            'timestamp' => time(),
            'sige_type' => 'md5',
        )
        $signature = $this->getSignature($params);
        $params['signature'] = $signature;
        */

        $client = Sender::getHttpClient();
        $response = $client->post(self::API_URL, array('form' => $params));
        $responseArr = json_decode($response->getBody(), true);
        $result = new StandardResult($message, $response);
        if (isset($responseArr['status'])) {
            if ($responseArr['status'] == 'success') {
                $result->setStatus(ResultInterface::STATUS_DELIVERED);
            } elseif ($responseArr['status'] == 'error') {
                $result->setStatus(ResultInterface::STATUS_FAILED);
            }
        }
        return $result;
    }

    /**
     * @param $params
     * @return string
     */
    protected function getSignature($params)
    {
        ksort($params);
        reset($params);
        $signature = array();
        foreach ($params as $key => $value) {
            $signature[] = $key . '=' . $value;
        }
        $signature = implode('&', $signature);
        return md5($this->appid . $this->appkey . $signature . $this->appid . $this->appkey);
    }

    /**
     * @param $number
     * @return bool
     */
    public function isNumberValid($number)
    {
        return true;
    }

    /**
     * @param $number
     * @return bool
     */
    public function isCountrySupported($number)
    {
        if (substr($number, 0, 3) !== '+86') {
            return false;
        }
        return true;
    }

    /**
     * @param $appid
     * @param $appkey
     */
    public function __construct($appid, $appkey)
    {
        $this->appid = $appid;
        $this->appkey = $appkey;
    }
}
