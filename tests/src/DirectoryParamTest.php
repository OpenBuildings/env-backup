<?php

namespace CL\EnvBackup\Test;

use CL\EnvBackup\DirectoryParam;
use CL\EnvBackup\DirectoryReflection;
use PHPUnit_Framework_TestCase;

/**
 * @group   env
 * @group   env.directory_param
 */
class DirectoryParamTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers CL\EnvBackup\DirectoryParam::__construct
     * @covers CL\EnvBackup\DirectoryParam::getContent
     */
    public function testConstruct()
    {
        $path = dirname(__FILE__).'/../test_dir';
        $contents = array('test' => 'test2');

        $directory = new DirectoryReflection($path, $contents);

        $param = new DirectoryParam($path, $contents);

        $this->assertEquals($directory, $param->getContent());
    }

    /**
     * @covers CL\EnvBackup\DirectoryParam::apply
     * @covers CL\EnvBackup\DirectoryParam::restore
     * @covers CL\EnvBackup\DirectoryParam::getBackup
     */
    public function testApplyRestore()
    {
        $path = dirname(__FILE__).'/../test_dir';
        $contents = array(
            'test' => 'test2',
            'inner' => array(
                'test3' => 'test3',
            )
        );

        $this->assertFileExists($path.'/test_file2.txt');
        $this->assertFileExists($path.'/inner_test_dir/test_file3.txt');

        $param = new DirectoryParam($path, $contents);
        $param->apply();

        $this->assertFileExists($path.'/test');
        $this->assertFileExists($path.'/inner/test3');

        $expectedContents = array(
            'test_file2.txt' => "test 2\n",
            'inner_test_dir' => array(
                'test_file3.txt' => "test 3\n",
            ),
        );

        $this->assertInstanceOf('CL\EnvBackup\DirectoryReflection', $param->getBackup());
        $this->assertEquals($path, $param->getBackup()->getPath());
        $this->assertEquals($expectedContents, $param->getBackup()->getContents());

        $param->restore();

        $this->assertFileExists($path.'/test_file2.txt');
        $this->assertFileExists($path.'/inner_test_dir/test_file3.txt');
    }
}
