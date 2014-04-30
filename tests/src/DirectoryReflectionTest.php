<?php

namespace CL\EnvBackup\Test;

use CL\EnvBackup\DirectoryReflection;
use CL\EnvBackup\VirtualDirectoryReflection;
use PHPUnit_Framework_TestCase;

/**
 * @group   directory_reflection
 */
class DirectoryReflectionTest extends PHPUnit_Framework_TestCase
{
    public function getTestDir()
    {
        return dirname(__FILE__).'/../test_dir';
    }

    public function dataLoadContents()
    {
        return array(
            array(
                $this->getTestDir().'/inner_test_dir',
                array(
                    'test_file3.txt' => "test 3\n",
                )
            ),
            array(
                $this->getTestDir(),
                array(
                    'test_file2.txt' => "test 2\n",
                    'inner_test_dir' => array(
                        'test_file3.txt' => "test 3\n",
                    ),
                )
            ),
        );
    }

    /**
     * @dataProvider dataLoadContents
     * @covers CL\EnvBackup\DirectoryReflection::loadContents
     */
    public function testLoadContents($path, $expectedContents)
    {
        $contents = DirectoryReflection::loadContents($path);

        $this->assertEquals($expectedContents, $contents);
    }

    /**
     * @covers CL\EnvBackup\DirectoryReflection::saveContents
     */
    public function testSaveContents()
    {
        $path = dirname(__FILE__).'/../test_dir2';

        DirectoryReflection::saveContents($path, array(
            'file1.txt' => 'test',
            'inner_dir' => array(
                'file2.txt' => 'test2'
            )
        ));

        $this->assertFileNotExists($path.'/tmp_file.txt');
        $this->assertFileExists($path.'/file1.txt');
        $this->assertFileExists($path.'/inner_dir/file2.txt');

        unlink($path.'/inner_dir/file2.txt');
        rmdir($path.'/inner_dir');
        unlink($path.'/file1.txt');
        rmdir($path);
    }

    /**
     * @covers CL\EnvBackup\DirectoryReflection::deleteContents
     */
    public function testDeleteContents()
    {
        $path = dirname(__FILE__).'/../test_dir3';

        mkdir($path);
        file_put_contents($path.'/test1.txt', 'asd');
        mkdir($path.'/inner');
        file_put_contents($path.'/inner/test2.txt', 'asd2');

        DirectoryReflection::deleteContents($path);

        $this->assertFileNotExists($path.'/test1.txt');
        $this->assertFileNotExists($path.'/inner/test2.txt');
    }

    /**
     * @covers CL\EnvBackup\DirectoryReflection::__construct
     * @covers CL\EnvBackup\DirectoryReflection::getContents
     * @covers CL\EnvBackup\DirectoryReflection::getExists
     * @covers CL\EnvBackup\DirectoryReflection::getPath
     */
    public function testConsturctExisting()
    {
        $dir = new DirectoryReflection($this->getTestDir());
        $this->assertEquals($this->getTestDir(), $dir->getPath());
        $this->assertTrue($dir->getExists());

        $expectedContents = array(
            'test_file2.txt' => "test 2\n",
            'inner_test_dir' => array(
                'test_file3.txt' => "test 3\n",
            ),
        );

        $this->assertEquals($expectedContents, $dir->getContents());
    }

    /**
     * @covers CL\EnvBackup\DirectoryReflection::__construct
     * @covers CL\EnvBackup\DirectoryReflection::getContents
     * @covers CL\EnvBackup\DirectoryReflection::getExists
     * @covers CL\EnvBackup\DirectoryReflection::getPath
     */
    public function testContructNotExisting()
    {
        $dir = new DirectoryReflection('__test//');
        $this->assertEquals('__test', $dir->getPath());
        $this->assertFalse($dir->getExists());
    }

    /**
     * @covers CL\EnvBackup\DirectoryReflection::__construct
     * @covers CL\EnvBackup\DirectoryReflection::getContents
     * @covers CL\EnvBackup\DirectoryReflection::getExists
     */
    public function testContructWithContents()
    {
        $contents = array(
            'file1.txt' => "test 1",
            'dir1' => array(
                'file2.txt' => "test 2",
            ),
        );

        $dir = new DirectoryReflection('__test', $contents);

        $this->assertEquals($contents, $dir->getContents());
        $this->assertTrue($dir->getExists());
    }

    /**
     * @covers CL\EnvBackup\DirectoryReflection::save
     */
    public function testSave()
    {
        $path = dirname(__FILE__).'/../test_dir5';

        mkdir($path);
        file_put_contents($path.'/test1.txt', 'asd');
        mkdir($path.'/inner');
        file_put_contents($path.'/inner/test2.txt', 'asd2');

        $contents = array(
            'file1.txt' => "test 1",
            'dir1' => array(
                'file2.txt' => "test 2",
            ),
        );

        $dir = new DirectoryReflection($path, $contents);

        $dir->save();

        $this->assertFileNotExists($path.'/test1.txt');
        $this->assertFileNotExists($path.'/inner/test2.txt');

        $this->assertFileExists($path.'/file1.txt');
        $this->assertFileExists($path.'/dir1/file2.txt');

        unlink($path.'/dir1/file2.txt');
        rmdir($path.'/dir1');
        unlink($path.'/file1.txt');
        rmdir($path);
    }
}
