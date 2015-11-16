<?php
namespace Eva\EvaSmsTest\ProvidersTest\SubmailTest;


use Eva\EvaSms\Message\TemplateMessage;
use Eva\EvaSms\Providers\Submail;
use Eva\EvaSms\Sender;
use GuzzleHttp\Handler\MockHandler;
use Eva\EvaSms\Result\ResultInterface;
use GuzzleHttp\Psr7\Response;

class SubmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Submail
     */
    protected $submail;

    public function setUp()
    {
        $this->submail = new Submail('key', 'secret');
    }

    protected function mock(Response $response)
    {
        $client = Sender::getHttpClient();
        $client->getConfig('handler')->setHandler(new MockHandler([$response]));
    }

    /**
     * @expectedException \Eva\EvaSms\Exception\UnsupportedException
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
        $this->mock(new Response(200, ['Content-Type' => 'javascript'], '{"status":"success"}'));
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
        $this->mock(new Response(200, ['Content-Type' => 'javascript'], '{"status":"error"}'));
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


