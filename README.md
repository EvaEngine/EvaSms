# EvaSms

[![Latest Stable Version](https://poser.pugx.org/evaengine/eva-sms/v/stable.svg)](https://packagist.org/packages/evaengine/eva-sms)
[![License](https://poser.pugx.org/evaengine/eva-sms/license.svg)](https://packagist.org/packages/evaengine/eva-sms)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/EvaEngine/EvaSms/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/EvaEngine/EvaSms/?branch=master)
[![Coverage Status](https://coveralls.io/repos/EvaEngine/EvaSms/badge.svg?branch=master)](https://coveralls.io/r/EvaEngine/EvaSms?branch=master)
[![Build Status](https://travis-ci.org/EvaEngine/EvaSms.svg?branch=master)](https://travis-ci.org/EvaEngine/EvaSms)

统一接口设计的短信发送库。

支持以下第三方短信接口服务：

- [Submail](http://submail.cn/)


## 使用方法

在composer.json中加入`"evaengine/eva-sms": "dev-master"`


代码示例:

``` php
$sender = new \Eva\EvaSms\Sender();
$sender::setDefaultTimeout(30); //设置默认超时时间，可省略
$sender->setProvider(new \Eva\EvaSms\Providers\Submail('AppID', 'AppKey'));

//基于模版发送短信
$result = $sender->sendTemplateMessage(
    'PhoneNumber', //手机号,目前只支持带国际区号的完整手机号，如 +8618512345678
    'TemplateID', //模版ID
    ['number' => '765321'] //模版变量
);

//发送自定义短信
$result = $sender->sendStandardMessage(
    'PhoneNumber', //手机号
    'ABC', //短信内容
);

var_dump($result); //显示发送结果
echo $result; //发送结果可以转换为字符串方便Log纪录
```
