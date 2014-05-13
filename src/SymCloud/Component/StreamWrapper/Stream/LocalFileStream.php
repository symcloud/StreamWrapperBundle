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

/**
 * Class LocalStream
 * @package SymCloud\Component\StreamWrapper\Stream
 */
class LocalFileStream extends Stream
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var resource
     */
    private $handle;

    /**
     * @var StreamMode
     */
    private $mode;

    function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function cast($castAs)
    {
        if ($this->handle) {
            return $this->handle;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if (!$this->handle) {
            return false;
        }

        $closed = fclose($this->handle);

        if ($closed) {
            $this->mode = null;
            $this->handle = null;
        }

        return $closed;
    }

    /**
     * {@inheritdoc}
     */
    public function eof()
    {
        if ($this->handle) {
            return feof($this->handle);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        if ($this->handle) {
            return fflush($this->handle);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function read($count = 0)
    {
        if (!$this->handle) {
            return false;
        }

        if (false === $this->mode->allowsRead()) {
            throw new \LogicException('The stream does not allow read.');
        }

        return fread($this->handle, $count);
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence)
    {
        if ($this->handle) {
            return 0 === fseek($this->handle, $offset, $whence);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function stat()
    {
        if ($this->handle) {
            return fstat($this->handle);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function tell()
    {
        if ($this->handle) {
            return ftell($this->handle);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function write($data)
    {
        if (!$this->handle) {
            return false;
        }

        if (false === $this->mode->allowsWrite()) {
            throw new \LogicException('The stream does not allow write.');
        }

        return fwrite($this->handle, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function open(StreamMode $mode = null)
    {
        try {
            $handle = @fopen($this->path, $mode->getMode());
        } catch (\Exception $e) {
            $handle = false;
        }

        if (false === $handle) {
            throw new \RuntimeException(sprintf('File "%s" cannot be opened', $this->path));
        }

        $this->mode = $mode;
        $this->handle = $handle;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function unlink()
    {
        if ($this->mode && $this->mode->impliesExistingContentDeletion()) {
            return @unlink($this->path);
        }

        return false;
    }
}
