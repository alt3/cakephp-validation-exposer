<?php
namespace Alt3\ValidationExposer\Lib;

use Cake\TestSuite\TestCase;

/**
 * Separate test class since this excpetion will only be thrown when no fixtures
 * are loaded,
 */
class ValidationExposerExceptionTest extends TestCase
{

    /**
     * setUp method executed before every testMethod.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * tearDown method executed after every testMethod.
     */
    public function tearDown()
    {
        parent::tearDownAfterClass();
    }

    /**
     * Make sure an exception is thrown when application has no tables.
     *
     * @expectedException \Cake\Network\Exception\InternalErrorException
     * @expectedExceptionMessage Could not find any tables in the application
     */
    public function testMissingTablesException()
    {
        $validator = new ValidationExposer();
    }
}
