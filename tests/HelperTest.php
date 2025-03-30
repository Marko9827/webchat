<?php

use PHPUnit\Framework\TestCase;
use marko9827\Webchat\Helper; 

class HelperTest extends TestCase {
    public function testSayHello() {
        $this->assertEquals("Working...", Helper::sayHello());
    }
}
