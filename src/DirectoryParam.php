<?php

namespace CL\EnvBackup;

/**
 * Make sure a file with a sertain content is present in the filesystem
 *
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class DirectoryParam implements ParamInterface
{
    /**
     * @var DirectoryReflection
     */
    protected $content;

    /**
     * What the value was before this param was applied
     * @var DirectoryReflection
     */
    protected $backup;

    /**
     * @param array $tree
     */
    public function __construct($path, array $tree)
    {
        $this->content = new DirectoryReflection($path, $tree);
    }

    /**
     * @return DirectoryReflection
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return DirectoryReflection
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
        $this->backup = new DirectoryReflection($this->content->getPath());

        $this->content->save();
    }

    /**
     * Restore the previous value
     * @return void
     */
    public function restore()
    {
        $this->backup->save();
    }
}
