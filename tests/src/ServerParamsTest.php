<?php

namespace CL\EnvBackup\Test;

use PHPUnit_Framework_TestCase;
use CL\EnvBackup\ServerParams;
use CL\EnvBackup\Notset;


/**
 * @group   env
 * @group   env.server_params
 */
class ServerParamsTest extends PHPUnit_Framework_TestCase
{
    public function test_methods()
    {
        $group = new ServerParams;

        $_SERVER = array('HOST' => 'some host', 'REQUEST_URI' => 'some uri');

        $this->assertEquals('some host', $group->get('HOST'));
        $this->assertEquals('some uri', $group->get('REQUEST_URI'));
        $this->assertInstanceOf('CL\EnvBackup\Notset', $group->get('SOME_VARIABLE'));

        $group->set('HOST', 'new host');
        $group->set('REQUEST_URI', new Notset);

        $this->assertEquals(array('HOST' => 'new host'), $_SERVER);

        $this->assertEquals('new host', $group->get('HOST'));

        $this->assertTrue($group->has('REQUEST_URI'));
        $this->assertTrue($group->has('HOST'));
        $this->assertTrue($group->has('SOME_VARIABLE'));
        $this->assertFalse($group->has('other variable'));
    }
}
