<?php

namespace CL\EnvBackup\Test;

use CL\EnvBackup\Env;
use PHPUnit_Framework_TestCase;

/**
 * @package CL\EnvBackup
 * @group   env
 */
class EnvTest extends PHPUnit_Framework_TestCase {

	public function test_construct()
	{
		$params = new DummyParams;

		$existing_env = array(
			'test_existing_key' => 'some value'
		);

		$expected_env = array(
			'test_key' => 'test value',
			'test_existing_key' => 'new value',
		);

		$params->variables = $existing_env;

		$env = new Env(array($params), $expected_env);

		$this->assertCount(1, $env->getGroups());
		$this->assertTrue($env->getGroups()->contains($params));
		$this->assertContains($params, $env->getGroups());

		$this->assertEquals($expected_env, $params->variables, 'Should set the variables when created with parameters');

		$env->restore();

		$this->assertEquals($existing_env, $params->variables, 'Should restore variables to original state');

		$env->backup(array('test_existing_key'));

		$this->assertEquals($existing_env, $params->variables, 'Backup should not affect variables');

		$env->set(array('test_existing_key' => 'new value'));

		$this->assertEquals(array('test_existing_key' => 'new value'), $params->variables);

		$env->restore();

		$this->assertEquals($existing_env, $params->variables);

		$this->assertSame($params, $env->groupForParamName('test_key'));

		$this->setExpectedException('\InvalidArgumentException');

		$env->groupForParamName('not_test');
	}


}
