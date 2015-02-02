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

    /**
     * @expectedException Eva\EvaSms\Exception\UnsupportedException
     */
    public function testUnsupportMessageType()
    {
        $sender = new Sender();
        $sender->setProvider(new Submail('appid', 'appkey'));
        $sender->sendStandardMessage('+8615200000000', 'test');
    }

    public function testClientTimeout()
    {
        $sender = new Sender();
        $sender::setDefaultTimeout(2);
        $client = $sender::getHttpClient();
        $this->assertInstanceOf('Guzzle\Http\Client', $client);
        $this->assertEquals(2, $client->getDefaultOption('timeout'));
    }

    public function testMobileValidate()
    {

    }
}

