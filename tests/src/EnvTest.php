<?php

namespace CL\EnvBackup\Test;

use CL\EnvBackup\Env;
use PHPUnit_Framework_TestCase;

/**
 * @group   env
 */
class EnvTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers CL\EnvBackup\Env::__construct
     */
    public function testConsturct()
    {
        $param1 = new DummyParam();
        $param2 = new DummyParam();

        $env = new Env($param1, $param2);

        $this->assertCount(2, $env->getParams());
        $this->assertContains($param1, $env->getParams());
        $this->assertContains($param2, $env->getParams());
    }

    /**
     * @covers CL\EnvBackup\Env::add
     * @covers CL\EnvBackup\Env::getParams
     */
    public function testAdd()
    {
        $param1 = new DummyParam();
        $param2 = new DummyParam();

        $env = new Env();
        $env->add($param1);
        $env->add($param2);

        $this->assertCount(2, $env->getParams());
        $this->assertContains($param1, $env->getParams());
        $this->assertContains($param2, $env->getParams());
    }

    /**
     * @covers CL\EnvBackup\Env::apply
     */
    public function testApply()
    {
        $param1 = $this->getMock('CL\EnvBackup\Test\DummyParam', array('apply'));
        $param1
            ->expects($this->once())
            ->method('apply');

        $param2 = $this->getMock('CL\EnvBackup\Test\DummyParam', array('apply'));
        $param2
            ->expects($this->once())
            ->method('apply');

        $env = new Env($param1, $param2);
        $env->apply();
    }

    /**
     * @covers CL\EnvBackup\Env::restore
     */
    public function testRestore()
    {
        $param1 = $this->getMock('CL\EnvBackup\Test\DummyParam', array('restore'));
        $param1
            ->expects($this->once())
            ->method('restore');

        $param2 = $this->getMock('CL\EnvBackup\Test\DummyParam', array('restore'));
        $param2
            ->expects($this->once())
            ->method('restore');

        $env = new Env($param1, $param2);
        $env->restore();
    }
}
