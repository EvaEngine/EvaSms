# EvaSms

统一接口设计的短信发送库。

支持以下第三方短信接口服务：

- [Submail](http://submail.cn/)


代码示例:

``` php
$sender = new \Eva\EvaSms\Sender();
$sender->setProvider(new \Eva\EvaSms\Providers\Submail('AppID', 'AppKey'));
$result = $sender->sendTemplateMessage('PhoneNumber', 'TemplateID', ['number' => '765321']);
var_dump($result);
echo $result; //Result could convert into string
```
