<?php

namespace CL\EnvBackup;

use DirectoryIterator;
use FilesystemIterator;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * This class is used to denote an 'not set' varible.
 * If a parameter was not present at all before a ->apply() call we use this class to remember that,
 * and later on '->restore()' the parameter is restored to its previous state e.g. 'not set'
 *
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class DirectoryReflection
{
    public static function loadContents($path)
    {
        $contents = array();

        $iterator = new DirectoryIterator($path);

        foreach ($iterator as $item) {
            if ($item->isDir() and ! $item->isDot()) {
                $contents[$item->getFilename()] = self::loadContents($item->getPathname());
            } elseif ($item->isFile()) {
                $contents[$item->getFilename()] = file_get_contents($item->getPathname());
            }
        }

        return $contents;
    }

    public static function saveContents($path, array $contents)
    {
        mkdir($path);

        foreach ($contents as $name => $content) {
            $itemPath = $path.DIRECTORY_SEPARATOR.$name;

            if (is_array($content)) {
                self::saveContents($itemPath, $content);
            } else {
                file_put_contents($itemPath, $content);
            }
        }

        return $contents;
    }

    public static function deleteContents($path)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $path,
                FilesystemIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isFile()) {
                unlink($item->getPathname());
            } else {
                rmdir($item->getPathname());
            }
        }

        rmdir($path);
    }

    protected $path;

    protected $exists;

    protected $contents = array();

    public function __construct($path, array $contents = null)
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR);

        if ($contents !== null) {
            $this->exists = true;
            $this->contents = $contents;
        } else {
            $this->exists = is_dir($this->path);

            if ($this->exists) {
                $this->contents = self::loadContents($this->path);
            }
        }
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function getExists()
    {
        return $this->exists;
    }

    public function save()
    {
        if (is_dir($this->path)) {
            self::deleteContents($this->path);
        }

        if ($this->exists) {
            self::saveContents($this->path, $this->contents);
        }

        return $this;
    }
}
