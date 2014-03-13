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
     * Set parameter
     * @return void
     */
    public function apply();

    /**
     * Restore the previous value
     * @return void
     */
    public function restore();
}
