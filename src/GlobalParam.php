<?php

namespace CL\EnvBackup;

use InvalidArgumentException;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class GlobalParam implements ParamInterface
{
    protected $name;
    protected $value;
    protected $backup;

    public static $acceptedNames = array(
        '_GET',
        '_POST',
        '_SERVER',
        '_FILES',
        '_COOKIE',
        '_SESSION',
    );

    public function __construct($name, array $value)
    {
        if ( ! in_array($name, self::$acceptedNames)) {
            throw new InvalidArgumentException(sprintf('%s is not one of _GET, _POST, _SERVER, _FILES, _COOKIE or _SESSION', $name));
        }
        $this->name = $name;
        $this->value = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getBackup()
    {
        return $this->backup;
    }

    /**
     * Set a super global
     * @param  string $name
     * @param  array  $value
     * @return void
     */
    public function apply()
    {
        global ${$this->name};

        $this->backup = ${$this->name};

        ${$this->name} = $this->value;
    }

    /**
     * Get a super global
     * @param  string $name
     * @return array
     */
    public function restore()
    {
        global ${$this->name};

        ${$this->name} = $this->backup;
    }
}
