<?php

namespace CL\EnvBackup\Test;

use CL\EnvBackup\FileParam;
use CL\EnvBackup\NotSet;
use PHPUnit_Framework_TestCase;

/**
 * @group   env
 * @group   env.global_param
 */
class FileParamTest extends PHPUnit_Framework_TestCase
{
    public function dataConstruct()
    {
        return array(
            array(dirname(__FILE__).'/../test_file.txt', 'test', FALSE),
            array(dirname(__FILE__).'/../dir/test_file.txt', 'test', TRUE),
        );
    }

    /**
     * @dataProvider dataConstruct
     * @covers CL\EnvBackup\FileParam::__construct
     * @covers CL\EnvBackup\FileParam::getName
     * @covers CL\EnvBackup\FileParam::getValue
     */
    public function testConstruct($name, $value, $is_invalid_argument)
    {
        if ($is_invalid_argument) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $param = new FileParam($name, $value);

        $this->assertEquals($name, $param->getName());
        $this->assertEquals($value, $param->getValue());

    }

    /**
     * @covers CL\EnvBackup\FileParam::apply
     * @covers CL\EnvBackup\FileParam::restore
     * @covers CL\EnvBackup\FileParam::getBackup
     */
    public function testApplyRestore()
    {
        $dir = dirname(__FILE__).'/../';

        $this->assertFileExists($dir.'test_file1.txt');
        $this->assertEquals("test\n", file_get_contents($dir.'test_file1.txt'));
        $this->assertFileNotExists($dir.'test_file2.txt');

        $param1 = new FileParam($dir.'test_file1.txt', 'content test 1');
        $param2 = new FileParam($dir.'test_file2.txt', 'content test 2');
        $param1->apply();
        $param2->apply();

        $this->assertFileExists($dir.'test_file1.txt');
        $this->assertFileExists($dir.'test_file2.txt');

        $this->assertEquals('content test 1', file_get_contents($dir.'test_file1.txt'));
        $this->assertEquals('content test 2', file_get_contents($dir.'test_file2.txt'));

        $this->assertEquals("test\n",     $param1->getBackup());
        $this->assertEquals(new NotSet(), $param2->getBackup());

        $param1->restore();
        $param2->restore();

        $this->assertFileExists($dir.'test_file1.txt');
        $this->assertEquals("test\n", file_get_contents($dir.'test_file1.txt'));
        $this->assertFileNotExists($dir.'test_file2.txt');
    }
}
