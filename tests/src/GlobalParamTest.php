<?php

namespace CL\EnvBackup\Test;

use CL\EnvBackup\GlobalParam;
use PHPUnit_Framework_TestCase;

/**
 * @group   env
 * @group   env.global_param
 */
class GlobalParamTest extends PHPUnit_Framework_TestCase
{
    public function dataConstruct()
    {
        return array(
            array('_POST', array('val1' => 1), FALSE),
            array('_GET', array('val1' => 1), FALSE),
            array('_SOMETHING', array('val1' => 1), TRUE),
        );
    }

    /**
     * @dataProvider dataConstruct
     * @covers CL\EnvBackup\GlobalParam::__construct
     * @covers CL\EnvBackup\GlobalParam::getName
     * @covers CL\EnvBackup\GlobalParam::getValue
     */
    public function testConstruct($name, $value, $is_invalid_argument)
    {
        if ($is_invalid_argument) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $param = new GlobalParam($name, $value);

        $this->assertEquals($name, $param->getName());
        $this->assertEquals($value, $param->getValue());

    }

    /**
     * @covers CL\EnvBackup\GlobalParam::apply
     * @covers CL\EnvBackup\GlobalParam::restore
     * @covers CL\EnvBackup\GlobalParam::getBackup
     */
    public function testApplyRestore()
    {
        $original = array('test' => 1);
        $_POST = $original;

        $new = array('changed' => 2);

        $param = new GlobalParam('_POST', $new);
        $param->apply();

        $this->assertEquals($new, $_POST);
        $this->assertEquals($original, $param->getBackup());

        $_POST = array('something' => '123');

        $param->restore();

        $this->assertEquals($original, $_POST);
    }
}
