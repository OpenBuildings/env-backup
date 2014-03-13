<?php

namespace CL\EnvBackup;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
interface ParamInterface
{
    /**
     * How to set a value on a parameter
     *
     * @return void
     */
    public function apply();

    /**
     * How to get a parameter
     *
     * @return void
     */
    public function restore();
}
