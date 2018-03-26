<?php

use PHPUnit\Framework\TestCase;

class PHPClientAddrTest extends TestCase
{
    protected function setUp()
    {
        $this->cliaddr = new PHPTools\PHPClientAddr\PHPClientAddr();

        $this->assertInstanceOf('PHPTools\PHPClientAddr\PHPClientAddr', $this->cliaddr);
    }

    public function testOne()
    {
        $this->assertInternalType('string', $this->cliaddr->ip);
        $this->assertInternalType('string', $this->cliaddr->hostname);
        $this->assertTrue(!!filter_var($this->cliaddr->ip, FILTER_VALIDATE_IP));

    }
}
