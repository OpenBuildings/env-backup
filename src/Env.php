<?php

namespace CL\EnvBackup;

use SplObjectStorage;

/**
 * A class for collectively executing "apply" and "restore" methods on multiple param's
 *
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Env
{
    /**
     * @var SplObjectStorage
     */
    protected $params;

    /**
     * @param array $params An array of ParamInterface objects
     */
    public function __construct(array $params = array())
    {
        $this->params = new SplObjectStorage();

        array_walk($params, array($this, 'add'));
    }

    /**
     * @param  ParamInterface $param
     * @return Env            $this
     */
    public function add(ParamInterface $param)
    {
        $this->params->attach($param);

        return $this;
    }

    /**
     * @return ParamInterface
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Execute "apply" method on all params
     * @return Env $this
     */
    public function apply()
    {
        foreach ($this->params as $param) {
            $param->apply();
        }

        return $this;
    }

    /**
     * Execute "restore" method on all params
     * @return Env $this
     */
    public function restore()
    {
        foreach ($this->params as $param) {
            $param->restore();
        }

        return $this;
    }
}
