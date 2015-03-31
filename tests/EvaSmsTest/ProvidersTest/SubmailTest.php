<?php
namespace Eva\EvaSmsTest\ProvidersTest\SubmailTest;


use Eva\EvaSms\Message\TemplateMessage;
use Eva\EvaSms\Result\StandardResult;
use Eva\EvaSms\Providers\Submail;
use Eva\EvaSms\Sender;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use Eva\EvaSms\Result\ResultInterface;

class SubmailTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->mock = new Mock([
            new Response(200, ['Content-Type' => 'javascript'], Stream::factory('{"status":"success"}')),
            new Response(200, ['Content-Type' => 'javascript'], Stream::factory('{"status":"error"}'))
        ]);
        $this->submail = new Submail('key', 'secret');
        $client = Sender::getHttpClient();
        $client->getEmitter()->attach($this->mock);
    }

    /**
     * @expectedException Eva\EvaSms\Exception\UnsupportedException
     */
    public function testNumberInvalid()
    {
        $this->submail->sendTemplateMessage(
            new TemplateMessage(
                '+810000000000',
                'temp_id',
                array('temp_key' => 'temp_value')
            )
        );
    }


    public function testResultSuccess()
    {
        $result = $this->submail->sendTemplateMessage(
            new TemplateMessage(
                '+8615200000000',
                'temp_id',
                array('temp_key' => 'temp_value')
            )
        );
        $this->assertEquals(200, $result->getRawResponse()->getStatusCode());
        $this->assertEquals(ResultInterface::STATUS_DELIVERED, $result->getStatus());
    }

    public function testResultError()
    {
        $result = $this->submail->sendTemplateMessage(
            new TemplateMessage(
                '+8615200000000',
                'temp_id',
                array('temp_key' => 'temp_value')
            )
        );
        $this->assertEquals(200, $result->getRawResponse()->getStatusCode());
        $this->assertEquals(ResultInterface::STATUS_FAILED, $result->getStatus());
    }

}


