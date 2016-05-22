<?php
namespace Alt3\ValidationExposer\Lib;

use Cake\TestSuite\TestCase;
use StdClass;

class ValidationExposerTest extends TestCase
{

    public $fixtures = [
        'plugin.Alt3/ValidationExposer.Articles',
        'plugin.Alt3/ValidationExposer.Authors',
        'plugin.Alt3/ValidationExposer.Tags'
    ];

    /**
     * @var array Default configuration settings every test will start with.
     */
    protected static $defaultConfig = [
        'excludedTables' => [
            'phinxlog'
        ]
    ];

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
     * Make sure our tests are using the expected configuration settings.
     */
    public function testDefaultConfiguration()
    {
        $expected = self::$defaultConfig;

        $lib = new ValidationExposer();
        $reflection = self::getReflection($lib);
        $result = $reflection->properties->_config->getValue($lib);

        $this->assertSame($expected, $result);
    }

    /**
     * Make sure excluded tables are merged into config.
     */
    public function testExcludedTablesConfigurationMerge()
    {
        $expected = [
            'excludedTables' => [
                'phinxlog',
                'tags'
            ]
        ];

        $lib = new ValidationExposer([
            'excludedTables' => [
                'tags'
            ]
        ]);
        $reflection = self::getReflection($lib);
        $result = $reflection->properties->_config->getValue($lib);

        $this->assertSame($expected, $result);
    }

    /**
     * Make sure hidden parts are merged into config.
     */
    public function testHiddenRulePartsConfigurationMerge()
    {
        $expected = [
            'excludedTables' => [
                'phinxlog'
            ],
            'hiddenRuleParts' => [
                'message'
            ]
        ];

        $lib = new ValidationExposer([
            'hiddenRuleParts' => [
                'message'
            ]
        ]);
        $reflection = self::getReflection($lib);
        $result = $reflection->properties->_config->getValue($lib);

        $this->assertSame($expected, $result);
    }

    /**
     * Make sure `tables()` method returns all loaded tables.
     */
    public function testTablesMethodSuccess()
    {
        $expected = [
            'articles',
            'authors',
            'tags'
        ];

        $validator = new ValidationExposer();
        $result = $validator->tables();

        $this->assertSame($expected, $result);
    }

    /**
     * Make sure `tables()` and `excludedTables()` methods honer exclusions.
     */
    public function testMethodsWithExcludedTables()
    {
        // test `tables()` method
        $expected = [
            'articles',
            'authors'
        ];

        $validator = new ValidationExposer([
            'excludedTables' => [
                'tags'
            ]
        ]);
        $result = $validator->tables();
        $this->assertSame($expected, $result);

        // test `excludedTables()` method
        $expected = [
            'phinxlog',
            'tags'
        ];

        $result = $validator->excludedTables();
        $this->assertSame($expected, $result);
    }

    /**
     * Convenience function to return an object with reflection class,
     * accessible protected methods and accessible protected properties.
     *
     * Accessing properties:
     *    $reflection = self::getReflection($this->lib);
     *    $config = $reflection->properties->_config->getValue($this->lib);
     */
    protected static function getReflection($object)
    {
        $obj = new stdClass();
        $obj->class = new \ReflectionClass(get_class($object));

        // make all methods accessible
        $obj->methods = new stdClass();
        $classMethods = $obj->class->getMethods();
        foreach ($classMethods as $method) {
            $methodName = $method->name;
            $obj->methods->{$methodName} = $obj->class->getMethod($methodName);
            $obj->methods->{$methodName}->setAccessible(true);
        }

        // make all properties accessible
        $obj->properties = new stdClass();
        $classProperties = $obj->class->getProperties();
        foreach ($classProperties as $property) {
            $propertyName = $property->name;
            $obj->properties->{$propertyName} = $obj->class->getProperty($propertyName);
            $obj->properties->{$propertyName}->setAccessible(true);
        }
        return $obj;
    }
}
