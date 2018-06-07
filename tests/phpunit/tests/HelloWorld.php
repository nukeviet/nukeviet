<?php

use PHPUnit\Framework\TestCase;

class HelloWorld extends TestCase
{
    public function testPushAndPop()
    {
        $this->assertArrayHasKey('k2', ['k1' => '12'], 'Fuck no key');
    }
}
