<?php

namespace CL\EnvBackup;

use ReflectionProperty;

/**
 * Using this param you can set static variables to classes, even if they ar protected / private
 *
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class StaticParam implements ParamInterface
{
    /**
     * @var ReflectionProperty
     */
    protected $property;

    /**
     * @var mixed
     */
    protected $backup;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $class
     * @param string $name
     * @param mixed  $value
     */
    public function __construct($class, $name, $value)
    {
        $this->property = new ReflectionProperty($class, $name);
        $this->property->setAccessible(true);
        $this->value = $value;
    }

    /**
     * @return ReflectionProperty
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getBackup()
    {
        return $this->backup;
    }

    /**
     * Set parameter
     * @return void
     */
    public function apply()
    {
        $this->backup = $this->property->getValue();
        $this->property->setValue($this->value);
    }

    /**
     * Restore the previous value
     * @return void
     */
    public function restore()
    {
        $this->property->setValue($this->backup);
    }
}
