<?php

namespace CL\EnvBackup;

use InvalidArgumentException;

/**
 * This param deals specifically with the _SERVER super global
 * You can modify arbitrary parameters of it.
 *
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class ServerParam implements ParamInterface
{
    /**
     * Taken from http://www.php.net/manual/en/reserved.variables.server.php
     *
     * @link http://www.php.net/manual/en/reserved.variables.server.php
     * @var array
     */
    public static $acceptedNames = array(
        'GATEWAY_INTERFACE',
        'SERVER_ADDR',
        'SERVER_NAME',
        'SERVER_SOFTWARE',
        'SERVER_PROTOCOL',
        'REQUEST_METHOD',
        'REQUEST_TIME',
        'REQUEST_TIME_FLOAT',
        'QUERY_STRING',
        'DOCUMENT_ROOT',
        'HTTP_ACCEPT',
        'HTTP_ACCEPT_CHARSET',
        'HTTP_ACCEPT_ENCODING',
        'HTTP_ACCEPT_LANGUAGE',
        'HTTP_CONNECTION',
        'HTTP_HOST',
        'HTTP_REFERER',
        'HTTP_USER_AGENT',
        'HTTPS',
        'REMOTE_ADDR',
        'REMOTE_HOST',
        'REMOTE_PORT',
        'REMOTE_USER',
        'REDIRECT_REMOTE_USER',
        'SCRIPT_FILENAME',
        'SERVER_ADMIN',
        'SERVER_PORT',
        'SERVER_SIGNATURE',
        'PATH_TRANSLATED',
        'SCRIPT_NAME',
        'REQUEST_URI',
        'PHP_AUTH_DIGEST',
        'PHP_AUTH_USER',
        'PHP_AUTH_PW',
        'AUTH_TYPE',
        'PATH_INFO',
        'ORIG_PATH_INFO',
    );

    /**
     * The name of the value inside _SERVER array
     * @var string
     */
    protected $name;

    /**
     * The value that should be set to a _SERVER value
     * @var string
     */
    protected $value;

    /**
     * What the value was before this param was applied
     * @var string
     */
    protected $backup;

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        if (! in_array($name, self::$acceptedNames)) {
            $message = sprintf('%s is not a _SERVER index, must be one of ServerParam::$asceptedNames', $name);
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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
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
        $this->backup = isset($_SERVER[$this->name]) ? $_SERVER[$this->name] : new NotSet();
        $_SERVER[$this->name] = $this->value;
    }

    /**
     * Restore the previous value
     * @return void
     */
    public function restore()
    {
        if ($this->backup instanceof NotSet) {
            unset($_SERVER[$this->name]);
        } else {
            $_SERVER[$this->name] = $this->backup;
        }
    }
}
