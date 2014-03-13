<?php

namespace CL\EnvBackup;

use SplObjectStorage;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Env
{
    protected $params;

    /**
     * You need to set "groups" to the Env in order for it to work
     *
     * @param array $groups     an array of Params objects (can be a key => value array for easier referance later)
     * @param array $parameters initial array of parameters to backup and set
     */
    public function __construct()
    {
        $this->params = new SplObjectStorage();

        $params = func_get_args();
        array_walk($params, array($this, 'add'));
    }

    public function add(ParamInterface $param)
    {
        $this->params->attach($param);

        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function apply()
    {
        foreach ($this->params as $param) {
            $param->apply();
        }

        return $this;
    }

    public function restore()
    {
        foreach ($this->params as $param) {
            $param->restore();
        }

        return $this;
    }
}
