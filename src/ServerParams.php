<?php

namespace CL\EnvBackup;

/**
 * @author Ivan Kerin <ikerin@gmail.com>
 * @copyright  (c) 2014 Clippings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
class ServerParams implements Params {

	/**
	 * Set a variable to on the $_SERVER super global, if Environment_Notset object is passed - unset it
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function set($name, $value)
	{
		if ($value instanceof Notset)
		{
			unset($_SERVER[$name]);
		}
		else
		{
			$_SERVER[$name] = $value;
		}
	}

	/**
	 * Get s $_SERVER super global variable, or Environment_Notset object if its not set
	 *
	 * @param  string $name
	 * @return mixed
	 */
	public function get($name)
	{
		return isset($_SERVER[$name]) ? $_SERVER[$name] : new Notset;
	}

	/**
	 * All capital letter names are considered $_SERVER vairables
	 *
	 * @param  string  $name
	 * @return boolean
	 */
	public function has($name)
	{
		return (preg_match('/^[A-Z_-]+$/', $name) OR isset($_SERVER[$name]));
	}
}
