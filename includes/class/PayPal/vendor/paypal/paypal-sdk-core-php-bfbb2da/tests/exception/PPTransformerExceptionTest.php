<?php

/**
 * Test class for PPTransformerException.
 *
 */
class PPTransformerExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PPTransformerException
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new PPTransformerException('Test PPTransformerException');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * @test
     */
    public function testPPTransformerException()
    {
    	$this->assertContains('Error on line', $this->object->errorMessage());
    	$this->assertContains('Test PPTransformerException', $this->object->errorMessage());

    }
}
?>
