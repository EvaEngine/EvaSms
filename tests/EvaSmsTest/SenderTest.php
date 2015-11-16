<?php
namespace Eva\EvaSmsTest;

use Eva\EvaSms\Providers\Submail;
use Eva\EvaSms\Sender;
use Guzzle\Http\Client;

class SenderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testClientTimeout()
    {
        $sender = new Sender();
        $client = $sender::getHttpClient();
        $this->assertInstanceOf('GuzzleHttp\Client', $client);
        $this->assertEquals(1, $client->getConfig('timeout'));
    }

    /**
     * @expectedException \Eva\EvaSms\Exception\UnsupportedException
     */
    public function testUnsupportMessageType()
    {
        $sender = new Sender();
        $sender->setProvider(new Submail('appid', 'appkey'));
        $sender->sendStandardMessage('+8615200000000', 'test');
    }

    /**
     * @expectedException  \RuntimeException
     */
    public function testNoProvider()
    {
        $sender = new Sender();
        $sender->sendStandardMessage('+8615200000000', 'test');
    }

    /**
     * @expectedException \Eva\EvaSms\Exception\InvalidNumberException
     */
    public function testNumberInvalid()
    {
        $sender = new Sender();
        $sender->setProvider(new Submail('appid', 'appkey'));
        $sender->sendStandardMessage('abc', 'test');
    }

    /**
     * @expectedException  \RuntimeException
     */
    public function testNoProviderForTemplate()
    {
        $sender = new Sender();
        $sender->sendTemplateMessage('+8615200000000', 'test');
    }

    /**
     * @expectedException \Eva\EvaSms\Exception\InvalidNumberException
     */
    public function testNumberInvalidForTemplate()
    {
        $sender = new Sender();
        $sender->setProvider(new Submail('appid', 'appkey'));
        $sender->sendTemplateMessage('abc', 'test');
    }


    public function testMobileValidate()
    {
        $sender = new Sender();
        $this->assertFalse($sender->isMobileNumberValid('abc'));
        $this->assertFalse($sender->isMobileNumberValid('+8612345678'));
        $this->assertFalse($sender->isMobileNumberValid('+abc'));
        $this->assertTrue($sender->isMobileNumberValid('+8618512345678'));
    }
}

