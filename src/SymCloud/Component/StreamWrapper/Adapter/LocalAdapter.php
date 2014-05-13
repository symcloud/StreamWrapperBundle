<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SymCloud\Component\StreamWrapper\Adapter;

use SymCloud\Component\StreamWrapper\Stream\LocalDirectoryStream;
use SymCloud\Component\StreamWrapper\Stream\LocalFileStream;
use SymCloud\Component\StreamWrapper\StreamWrapperManager;
use SymCloud\Component\StreamWrapper\Util\Path;

class LocalAdapter implements AdapterInterface, StreamFactoryInterface
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var boolean
     */
    private $create;

    function __construct($directory, $create)
    {
        $this->directory = $directory;
        $this->create = $create;
    }

    /**
     * {@inheritdoc}
     */
    public function createStream($key, $domain)
    {
        $path = $this->computePath($key);

        if (is_dir($path)) {
            return new LocalDirectoryStream($path, $key, $domain);
        } else {
            return new LocalFileStream($path);
        }
    }

    /**
     * computes the path from the specified key
     * @param string $key the key which for to compute the path
     * @return string path
     */
    protected function computePath($key)
    {
        $this->ensureDirectoryExists($this->directory, $this->create);

        return $this->normalizePath($this->directory . '/' . $key);
    }

    /**
     * normalizes the given path
     * @param string $path
     * @throws \OutOfBoundsException
     * @return string
     */
    protected function normalizePath($path)
    {
        $path = Path::normalize($path);

        if (0 !== strpos($path, $this->directory)) {
            throw new \OutOfBoundsException(sprintf('The path "%s" is out of the filesystem.', $path));
        }

        return $path;
    }

    /**
     * ensures the specified directory exists, creates it if it does not
     * @param string $directory path of the directory to test
     * @param boolean $create whether to create the directory if it does not exist
     * @throws \RuntimeException
     */
    protected function ensureDirectoryExists($directory, $create = false)
    {
        if (!is_dir($directory)) {
            if (!$create) {
                throw new \RuntimeException(sprintf('The directory "%s" does not exist.', $directory));
            }

            $this->createDirectory($directory);
        }
    }

    /**
     * creates the specified directory and its parents
     * @param string $directory path of the directory to create
     * @throws \RuntimeException
     */
    protected function createDirectory($directory)
    {
        $umask = umask(0);
        $created = mkdir($directory, 0777, true);
        umask($umask);

        if (!$created) {
            throw new \RuntimeException(sprintf('The directory \'%s\' could not be created.', $directory));
        }
    }

    /**
     * indicates whether the file exists
     * @param string $key
     * @return boolean
     */
    public function exists($key)
    {
        return file_exists($this->computePath($key));
    }
}
