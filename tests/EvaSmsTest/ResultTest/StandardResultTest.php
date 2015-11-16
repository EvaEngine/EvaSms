<?php
namespace Eva\EvaSmsTest\ResultTest;

use Eva\EvaSms\Message\StandardMessage;
use Eva\EvaSms\Result\StandardResult;
use GuzzleHttp\Psr7\Response;

class StandardResultTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testResult()
    {
        $message = new StandardMessage('+8615212345678', 'MessageBody');
        $result = new StandardResult($message, new Response(200));
        $this->assertTrue(is_numeric($result->getSentTimestamp()));
        $this->assertJson($result->__toString());
        $this->assertJson($message->__toString());
    }
}
