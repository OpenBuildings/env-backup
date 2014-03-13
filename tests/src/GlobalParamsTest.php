<?php

namespace CL\EnvBackup\Test;

use CL\EnvBackup\GlobalParams;
use PHPUnit_Framework_TestCase;

/**
 * @package Openbuildings\EnvironmentBackup
 * @group   env
 * @group   env.global_params
 */
class GlobalParamsTest extends PHPUnit_Framework_TestCase {

	public function test_methods()
	{
		$group = new GlobalParams;

		$_POST = array('some name' => 'some value');
		$_GET = array('some 2 name' => 'some 2 value');

		$this->assertEquals($_POST, $group->get('_POST'));
		$this->assertEquals($_GET, $group->get('_GET'));
		$this->assertEquals(array(), $group->get('_FILES'));

		$group->set('_POST', array('new name' => 'new value'));

		$this->assertEquals(array('new name' => 'new value'), $group->get('_POST'));
		$this->assertEquals(array('new name' => 'new value'), $_POST);

		$this->assertTrue($group->has('_POST'));
		$this->assertTrue($group->has('_GET'));
		$this->assertTrue($group->has('_SERVER'));
		$this->assertTrue($group->has('_FILES'));
		$this->assertFalse($group->has('_TEST'));
	}
}
