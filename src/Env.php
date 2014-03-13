<?php

namespace CL\EnvBackup;

use SplObjectStorage;
use InvalidArgumentException;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Env
{
    protected $groups;
    protected $backup = array();

    /**
     * You need to set "groups" to the Env in order for it to work
     *
     * @param array $groups     an array of Params objects (can be a key => value array for easier referance later)
     * @param array $parameters initial array of parameters to backup and set
     */
    public function __construct(array $groups = array(), array $parameters = array())
    {
        $this->groups = new SplObjectStorage();

        if ($groups) {
            foreach ($groups as $group) {
                $this->groups->attach($group);
            }
        }

        if ($parameters) {
            $this->backupAndSet($parameters);
        }
    }

    /**
     * Restores all the variables from the backup, clears the backup (second "restore" will have no effect)
     *
     * @return Environment $this
     */
    public function restore()
    {
        $this->set($this->backup);
        $this->backup = array();

        return $this;
    }

    /**
     * Getter / Setter of the array of groups
     *
     * get a group by key, set a group by key / value or set all of them with an array
     *
     * @return CL\EnvBackup\Params
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Backup the parameters and the set them
     *
     * @param  array                    $parameters array of parameters
     * @return CL\EnvBackup\Env         $this
     * @throws InvalidArgumentException If there is parameter for which a group does not exist
     */
    public function backupAndSet(array $parameters)
    {
        $this
            ->backup(array_keys($parameters))
            ->set($parameters);

        return $this;
    }

    /**
     * Find out which group a variable belongs to
     *
     * @param  string                   $name
     * @return CL\EnvBackup\Params
     * @throws InvalidArgumentException If no variable is found
     */
    public function groupForParamName($name)
    {
        foreach ($this->groups as $params) {
            if ($params->has($name)) {
                return $params;
            }
        }

        throw new InvalidArgumentException(sprintf("Environment variable %s does not belong to any group", $name));
    }

    /**
     * Backup the given parameters
     *
     * @param  array                    $parameters the names of the parameters
     * @return CL\EnvBackup\Env         $this
     * @throws InvalidArgumentException If there is parameter for which a group does not exist
     */
    public function backup(array $parameters)
    {
        foreach ($parameters as $name) {
            $this->backup[$name] = $this->groupForParamName($name)->get($name);
        }

        return $this;
    }

    /**
     * Set the parameters, using groups
     *
     * @param  array                    $parameters name => value of parameters
     * @return CL\EnvBackup\Env         $this
     * @throws InvalidArgumentException If there is parameter for which a group does not exist
     */
    public function set(array $parameters)
    {
        foreach ($parameters as $name => $value) {
            $this->groupForParamName($name)->set($name, $value);
        }

        return $this;
    }
}
