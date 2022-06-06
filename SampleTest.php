<?php

use PHPUnit\Framework\TestCase;
require_once 'Sample.php';

class SampleTest extends TestCase {
    public function testSendMail() 
    {
        $response = sendMail();
        $this->assertEquals(202, $response);
    }
}