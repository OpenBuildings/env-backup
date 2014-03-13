<?php

namespace CL\EnvBackup;

use ReflectionClass;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class StaticParams implements Params
{
    /**
     * Set a static variable on a class using reflections, will get private / public ones too
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    public function set($name, $value)
    {
        list($class, $name) = explode('::$', $name, 2);

        $class = new ReflectionClass($class);
        $property = $class->getProperty($name);
        $property->setAccessible(true);
        $property->setValue($value);
    }

    /**
     * Get a static variable of a class, using reflections, will get private / public ones too
     *
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {
        list($class, $name) = explode('::$', $name, 2);

        $class = new ReflectionClass($class);
        $property = $class->getProperty($name);
        $property->setAccessible(true);

        return $property->getValue();
    }

    /**
     * Check if the parameter is a static variable
     *
     * @param  string  $name
     * @return boolean
     */
    public function has($name)
    {
        return strpos($name, '::$') !== false;
    }
}
