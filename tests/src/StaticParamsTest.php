<?php

namespace CL\EnvBackup\Test;

use PHPUnit_Framework_TestCase;
use CL\EnvBackup\StaticParams;
use CL\EnvBackup\Notset;

class StaticParamsDummy {

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
 * @package Openbuildings\EnvironmentBackup
 * @group   env
 * @group   env.static_params
 */
class StaticParamsTest extends PHPUnit_Framework_TestCase {


	public function test_methods()
	{
		$group = new StaticParams;

		$this->assertEquals('value 1', $group->get('CL\EnvBackup\Test\StaticParamsDummy::$varPublic'));
		$this->assertEquals('value 2', $group->get('CL\EnvBackup\Test\StaticParamsDummy::$varProtected'));
		$this->assertEquals('value 3', $group->get('CL\EnvBackup\Test\StaticParamsDummy::$varPrivate'));

		$group->set('CL\EnvBackup\Test\StaticParamsDummy::$varPublic', 'new 1');
		$group->set('CL\EnvBackup\Test\StaticParamsDummy::$varProtected', 'new 2');
		$group->set('CL\EnvBackup\Test\StaticParamsDummy::$varPrivate', 'new 3');

		$this->assertEquals('new 1', StaticParamsDummy::$varPublic);
		$this->assertEquals('new 2', StaticParamsDummy::getVarProtected());
		$this->assertEquals('new 3', StaticParamsDummy::getVarPrivate());

		$this->assertEquals('new 1', $group->get('CL\EnvBackup\Test\StaticParamsDummy::$varPublic'));
		$this->assertEquals('new 2', $group->get('CL\EnvBackup\Test\StaticParamsDummy::$varProtected'));
		$this->assertEquals('new 3', $group->get('CL\EnvBackup\Test\StaticParamsDummy::$varPrivate'));

		$this->assertTrue($group->has('CL\EnvBackup\Test\StaticParamsDummy::$varPublic'));
		$this->assertTrue($group->has('Env::$some'));
		$this->assertFalse($group->has('other variable'));
	}
}
