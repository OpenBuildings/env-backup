<?php

namespace CL\EnvBackup;

use InvalidArgumentException;

/**
 * Make sure a file with a sertain content is present in the filesystem
 *
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class FileParam implements ParamInterface
{

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
        if (! is_dir(dirname($name))) {
            $message = sprintf('Directy %s must be present', dirname($name));
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
        $this->backup = is_file($this->name) ? file_get_contents($this->name) : new NotSet();

        file_put_contents($this->name, $this->value);
    }

    /**
     * Restore the previous value
     * @return void
     */
    public function restore()
    {
        if ($this->backup instanceof NotSet) {
            if (is_file($this->name)) {
                unlink($this->name);
            }
        } else {
            file_put_contents($this->name, $this->backup);
        }
    }
}
