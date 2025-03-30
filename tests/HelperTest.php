<?php

use PHPUnit\Framework\TestCase;
use Webchat\Helper; 

class HelperTest extends TestCase {
    public function testSayHello() {
        $this->assertEquals("Working...", Helper::sayHello());
    }
}
