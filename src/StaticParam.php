<?php

namespace CL\EnvBackup;

use ReflectionProperty;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class StaticParam implements ParamInterface
{
    protected $property;
    protected $backup;
    protected $value;

    public function __construct($class, $name, $value)
    {
        $this->property = new ReflectionProperty($class, $name);
        $this->property->setAccessible(true);
        $this->value = $value;
    }

    public function getProperty()
    {
        return $this->property;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getBackup()
    {
        return $this->backup;
    }

    public function apply()
    {
        $this->backup = $this->property->getValue();
        $this->property->setValue($this->value);
    }

    public function restore()
    {
        $this->property->setValue($this->backup);
    }
}
