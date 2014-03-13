<?php

namespace CL\EnvBackup;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class GlobalParams implements Params
{
    /**
     * Set a super global
     * @param string $name
     * @param array  $value
     */
    public function set($name, $value)
    {
        global $$name;

        $$name = $value;
    }

    /**
     * Get a super global
     * @param  string $name
     * @return array
     */
    public function get($name)
    {
        global $$name;

        return $$name;
    }

    /**
     * A super global is considered one that is _GET, _POST, _SERVER, _FILES, _COOKIE or _SESSION
     * @param  string  $name
     * @return boolean
     */
    public function has($name)
    {
        return in_array($name, array('_GET', '_POST', '_SERVER', '_FILES', '_COOKIE', '_SESSION'));
    }
}
