<?php

namespace CL\EnvBackup\Test;

use PHPUnit_Framework_TestCase;
use CL\EnvBackup\StaticParam;

class StaticParamDummy
{
    public static $varPublic       = 'value 1';
    protected static $varProtected = 'value 2';
    private static $varPrivate     = 'value 3';

    public static function getVarProtected()
    {
        return self::$varProtected;
    }

    public static function getVarPrivate()
    {
        return self::$varPrivate;
    }
}

/**
 * @group   env
 * @group   env.static_params
 */
class StaticParamTest extends PHPUnit_Framework_TestCase
{
    public function dataConstruct()
    {
        return array(
            array('CL\EnvBackup\Test\StaticParamDummy', 'varPublic', 'new value 1', 'value 1', FALSE),
            array('CL\EnvBackup\Test\StaticParamDummy', 'varProtected', 'new value 2', 'value 2', FALSE),
            array('CL\EnvBackup\Test\StaticParamDummy', 'varPrivate', 'new value 3', 'value 3', FALSE),
            array('CL\EnvBackup\Test\StaticParamDummy', 'unknownVal', 'new value 4', 'value 4', TRUE),
        );
    }

    /**
     * @dataProvider dataConstruct
     * @covers CL\EnvBackup\StaticParam::__construct
     * @covers CL\EnvBackup\StaticParam::getProperty
     * @covers CL\EnvBackup\StaticParam::getValue
     */
    public function testConstruct($class, $property, $new_value, $value, $is_invalid_argument)
    {
        if ($is_invalid_argument) {
            $this->setExpectedException('ReflectionException');
        }

        $param = new StaticParam($class, $property, $new_value);

        $this->assertInstanceOf('ReflectionProperty', $param->getProperty());
        $this->assertEquals($new_value, $param->getValue());
        $this->assertEquals($value, $param->getProperty()->getValue());
        $this->assertEquals($property, $param->getProperty()->getName());
    }

    /**
     * @covers CL\EnvBackup\StaticParam::apply
     * @covers CL\EnvBackup\StaticParam::restore
     * @covers CL\EnvBackup\StaticParam::getBackup
     */
    public function testApplyRestore()
    {
        $expected1 = 'new val 1';
        $expected2 = 'new val 2';
        $expected3 = 'new val 3';

        $original1 = StaticParamDummy::$varPublic;
        $original2 = StaticParamDummy::getVarProtected();
        $original3 = StaticParamDummy::getVarPrivate();

        $param1 = new StaticParam('CL\EnvBackup\Test\StaticParamDummy', 'varPublic', $expected1);
        $param2 = new StaticParam('CL\EnvBackup\Test\StaticParamDummy', 'varProtected', $expected2);
        $param3 = new StaticParam('CL\EnvBackup\Test\StaticParamDummy', 'varPrivate', $expected3);

        $param1->apply();
        $param2->apply();
        $param3->apply();

        $this->assertEquals($expected1, StaticParamDummy::$varPublic);
        $this->assertEquals($expected2, StaticParamDummy::getVarProtected());
        $this->assertEquals($expected3, StaticParamDummy::getVarPrivate());

        $this->assertEquals($original1, $param1->getBackup());
        $this->assertEquals($original2, $param2->getBackup());
        $this->assertEquals($original3, $param3->getBackup());

        StaticParamDummy::$varPublic = '11111';

        $param1->restore();
        $param2->restore();
        $param3->restore();

        $this->assertEquals($original1, StaticParamDummy::$varPublic);
        $this->assertEquals($original2, StaticParamDummy::getVarProtected());
        $this->assertEquals($original3, StaticParamDummy::getVarPrivate());
    }
}
