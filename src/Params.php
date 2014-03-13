<?php

namespace CL\EnvBackup;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
interface Params
{
    /**
     * How to set a value on a parameter
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    public function set($name, $value);

    /**
     * How to get a parameter
     *
     * @param string $name
     */
    public function get($name);

    /**
     * Check if this name belongs to this group
     *
     * @param  string  $name
     * @return boolean
     */
    public function has($name);
}
