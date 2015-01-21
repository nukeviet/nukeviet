<?php
class FormatterFactoryTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @test
	 */
	public function testValidBinding() {
		$this->assertEquals('PPNVPFormatter', get_class(FormatterFactory::factory('NV')));
		$this->assertEquals('PPSOAPFormatter', get_class(FormatterFactory::factory('SOAP')));
	}
	
	/**
	 * @test
	 */
	public function testInvalidBinding() {
		$this->setExpectedException('InvalidArgumentException');
		FormatterFactory::factory('Unknown');
	}
}