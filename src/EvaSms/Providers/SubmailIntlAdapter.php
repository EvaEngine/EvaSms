<?php
/**
 * Created by PhpStorm.
 * User: yuanxiaodan
 * Date: 9/22/16
 * Time: 1:57 PM
 */

namespace Eva\EvaSms\Providers;

use Eva\EvaSms\Exception\InvalidNumberException;
use Eva\EvaSms\Exception\UnsupportedCountryException;
use Eva\EvaSms\Exception\UnsupportedException;
use Eva\EvaSms\Message\MessageInterface;
use Eva\EvaSms\Message\StandardMessage;
use Eva\EvaSms\Message\TemplateMessage;
use Eva\EvaSms\Result\ResultInterface;
use Eva\EvaSms\Result\StandardResult;
use Eva\EvaSms\Sender;


class SubmailIntlAdapter implements ProviderInterface
{
    const API_URL_INTL = 'https://api.submail.cn/internationalsms/xsend.json';

    protected $appid;
    protected $appkey;
    protected $signature;

    public function sendStandardMessage(StandardMessage $message)
    {
        $this->submailDom->sendStandardMessage($message);
    }

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
        $response = $client->post(self::API_URL_INTL, array('body' => $params));
        $responseArr = $response->json();
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

    protected function getSignature($params)
    {
        $this->submailDom->getSignature($params);
    }

    /**
     * @param $number
     * @return ResultInterface
     */
    public function isNumberValid($number)
    {
        return $this->submailDom->isNumberValid($number);
    }

    /**
     * @param $number
     * @return bool
     */
    public function isCountrySupported($number)
    {
        // 需要添加前缀白名单满足美国之外
        if (substr($number, 0, 2) !== '+1') {
            return false;
        }
        return true;
    }

    private $submailDom;

    public function __construct($appid, $appkey)
    {
        $submailDom = new Submail($appid, $appkey);
        $this->submailDom = $submailDom;
        $this->appid = $appid;
        $this->appkey = $appkey;
    }

}
