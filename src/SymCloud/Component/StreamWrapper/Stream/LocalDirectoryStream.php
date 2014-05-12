<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SymCloud\Component\StreamWrapper\Stream;

use SymCloud\Component\StreamWrapper\Exception\NotSupportedException;

class LocalDirectoryStream implements StreamInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var resource
     */
    private $handle;

    function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function cast($castAs)
    {
        throw new NotSupportedException();
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        closedir($this->handle);
        $this->handle = null;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function eof()
    {
        throw new NotSupportedException();
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        throw new NotSupportedException();
    }

    /**
     * {@inheritdoc}
     */
    public function read($count = 0)
    {
        if (!$this->handle) {
            return false;
        }

        return readdir($this->handle);
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence)
    {
        throw new NotSupportedException();
    }

    /**
     * {@inheritdoc}
     */
    public function stat()
    {
        throw new NotSupportedException();
    }

    /**
     * {@inheritdoc}
     */
    public function tell()
    {
        throw new NotSupportedException();
    }

    /**
     * {@inheritdoc}
     */
    public function write($data)
    {
        throw new NotSupportedException();
    }

    /**
     * {@inheritdoc}
     */
    public function open(StreamMode $mode = null)
    {
        $handle = opendir($this->path);

        if (false === $handle) {
            throw new \RuntimeException(sprintf('File "%s" cannot be opened', $this->path));
        }

        $this->handle = $handle;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function unlink()
    {
        throw new NotSupportedException();
    }
}
