<?php
namespace Eva\EvaSmsTest\ResultTest;


use Eva\EvaSms\Message\StandardMessage;
use Eva\EvaSms\Result\StandardResult;
use GuzzleHttp\Message\Response;

class StandardResultTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testResult()
    {
        $result = new StandardResult(new StandardMessage('+8615212345678', 'MessageBody'), new Response(200));
        $this->assertTrue(is_numeric($result->getSentTimestamp()));
    }
}

