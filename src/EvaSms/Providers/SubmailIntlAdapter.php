<?php
/**
 * Created by PhpStorm.
 * User: jiangsongrong
 * Date: 9/22/16
 * Time: 1:57 PM
 */

namespace Eva\EvaSms\Providers;

use Eva\EvaSms\Message\StandardMessage;
use Eva\EvaSms\Message\TemplateMessage;
use Eva\EvaSms\Result\ResultInterface;
use Eva\EvaSms\Providers\Submail;


class SubmailIntlAdapter implements ProviderInterface
{
    /*
     * methods already exist
     */
    const API_URL_INTL = 'https://api.submail.cn/internationalsms/xsend.json';

    public function sendTemplateMessage(TemplateMessage $message)
    {
        $this->submailDom->sendTemplateMessage($message);
    }

    public function sendStandardMessage(StandardMessage $message)
    {
        $this->submailDom->sendStandardMessage($message);
    }

    /**
     * @param $number
     * @return ResultInterface
     */
    public function isNumberValid($number)
    {
        $this->submailDom->isNumberValid($number);
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

    /*
     * 持有需要被适配的接口Submail对象
     */
    private $submailDom;

    function __construct($submailDom)
    {
        $this->submailDom = $submailDom;
    }

}