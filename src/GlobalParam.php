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

    /**
     * @param string $name
     * @param array  $value
     */
    public function __construct($name, array $value)
    {
        if (! in_array($name, self::$acceptedNames)) {
            $message = sprintf('%s is not one of _GET, _POST, _SERVER, _FILES, _COOKIE or _SESSION', $name);
            throw new InvalidArgumentException($message);
        }
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
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
        global ${$this->name};

        $this->backup = ${$this->name};

        ${$this->name} = $this->value;
    }

    /**
     * Restore the previous value
     * @return void
     */
    public function restore()
    {
        global ${$this->name};

        ${$this->name} = $this->backup;
    }
}
