<?php

namespace CL\EnvBackup\Test;

use PHPUnit_Framework_TestCase;
use CL\EnvBackup\ServerParam;
use CL\EnvBackup\NotSet;

/**
 * @group   env
 * @group   env.server_params
 */
class ServerParamTest extends PHPUnit_Framework_TestCase
{
    public function dataAcceptedNamesByType()
    {
        return array(
            array(ServerParam::BROWSER, ServerParam::$acceptedBrowserNames),
            array(ServerParam::CLI, ServerParam::$acceptedCliNames),
            array('test', null),
        );
    }

    /**
     * @dataProvider dataAcceptedNamesByType
     * @covers CL\EnvBackup\ServerParam::acceptedNamesByType
     */
    public function testAcceptedNamesByType($type, $expected)
    {
        $names = ServerParam::acceptedNamesByType($type);

        $this->assertEquals($expected, $names);
    }

    public function dataConstruct()
    {
        return array(
            array('GATEWAY_INTERFACE', 'interface', ServerParam::BROWSER, FALSE),
            array('REQUEST_TIME', 'time', ServerParam::BROWSER, FALSE),
            array('_SOMETHING', 'time', ServerParam::BROWSER, TRUE),
            array('HOME', 'time', ServerParam::BROWSER, TRUE),
            array('HOME', 'time', ServerParam::CLI, FALSE),
        );
    }

    /**
     * @dataProvider dataConstruct
     * @covers CL\EnvBackup\ServerParam::__construct
     * @covers CL\EnvBackup\ServerParam::getName
     * @covers CL\EnvBackup\ServerParam::getValue
     */
    public function testConstruct($name, $value, $type, $is_invalid_argument)
    {
        if ($is_invalid_argument) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $param = new ServerParam($name, $value, $type);

        $this->assertEquals($name, $param->getName());
        $this->assertEquals($value, $param->getValue());
    }

    /**
     * @covers CL\EnvBackup\ServerParam::apply
     * @covers CL\EnvBackup\ServerParam::restore
     * @covers CL\EnvBackup\ServerParam::getBackup
     */
    public function testApplyRestore()
    {
        $original = array('GATEWAY_INTERFACE' => 'original interface', 'REQUEST_TIME' => 'time');
        $_SERVER = $original;

        $expected = array('GATEWAY_INTERFACE' => 'interface', 'REQUEST_TIME' => 'time', 'REMOTE_ADDR' => 'addr');

        $param1 = new ServerParam('GATEWAY_INTERFACE', 'interface');
        $param2 = new ServerParam('REMOTE_ADDR', 'addr');
        $param1->apply();
        $param2->apply();

        $this->assertEquals($expected, $_SERVER);
        $this->assertEquals($original['GATEWAY_INTERFACE'], $param1->getBackup());
        $this->assertEquals(new NotSet(), $param2->getBackup());

        $_SERVER['GATEWAY_INTERFACE'] = 'changed';

        $param1->restore();
        $param2->restore();

        $this->assertEquals($original, $_SERVER);
    }
}
