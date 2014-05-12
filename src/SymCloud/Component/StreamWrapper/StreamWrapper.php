<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SymCloud\Component\StreamWrapper;

use SymCloud\Component\StreamWrapper\Stream\StreamInterface;
use SymCloud\Component\StreamWrapper\Stream\StreamMode;
use Symfony\Component\Intl\Exception\NotImplementedException;

/**
 * Class StreamWrapper
 * @package SymCloud\Component\StreamWrapper
 */
class StreamWrapper implements StreamWrapperInterface
{
    /**
     * @var StreamInterface wrapped stream
     */
    private $stream;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function dir_closedir()
    {
        if ($this->stream) {
            return $this->stream->close();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function dir_opendir($path, $options)
    {
        $this->stream = $this->createStream($path);

        return $this->stream->open();
    }

    /**
     * {@inheritdoc}
     */
    public function dir_readdir()
    {
        if ($this->stream) {
            return $this->stream->read();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function dir_rewinddir()
    {
        throw new NotImplementedException('stream_lock');
    }

    /**
     * {@inheritdoc}
     */
    public function mkdir($path, $mode, $options)
    {
        throw new NotImplementedException('stream_lock');
    }

    /**
     * {@inheritdoc}
     */
    public function rename($path_from, $path_to)
    {
        throw new NotImplementedException('stream_lock');
    }

    /**
     * {@inheritdoc}
     */
    public function rmdir($path, $options)
    {
        throw new NotImplementedException('stream_lock');
    }

    /**
     * {@inheritdoc}
     */
    public function stream_cast($castAs)
    {
        if ($this->stream) {
            return $this->stream->cast($castAs);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function stream_close()
    {
        if ($this->stream) {
            $this->stream->close();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stream_eof()
    {
        if ($this->stream) {
            return $this->stream->eof();
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function stream_flush()
    {
        if ($this->stream) {
            return $this->stream->flush();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function stream_lock($operation)
    {
        throw new NotImplementedException('stream_lock');
    }

    /**
     * {@inheritdoc}
     */
    public function stream_open($path, $mode, $options, &$opened_path)
    {
        $this->stream = $this->createStream($path);

        return $this->stream->open($this->createStreamMode($mode));
    }

    /**
     * {@inheritdoc}
     */
    public function stream_read($count)
    {
        if ($this->stream) {
            return $this->stream->read($count);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function stream_seek($offset, $whence = SEEK_SET)
    {
        if ($this->stream) {
            return $this->stream->seek($offset, $whence);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function stream_set_option($option, $arg1, $arg2)
    {
        throw new NotImplementedException('stream_set_option');
    }

    /**
     * {@inheritdoc}
     */
    public function stream_stat()
    {
        if ($this->stream) {
            return $this->stream->stat();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function stream_tell()
    {
        if ($this->stream) {
            return $this->stream->tell();
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function stream_write($data)
    {
        if ($this->stream) {
            return $this->stream->write($data);
        }

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function unlink($path)
    {
        $stream = $this->createStream($path);

        try {
            $stream->open($this->createStreamMode('w+'));
        } catch (\RuntimeException $e) {
            return false;
        }

        return $stream->unlink();
    }

    /**
     * {@inheritdoc}
     */
    public function url_stat($path, $flags)
    {
        $stream = $this->createStream($path);

        try {
            $stream->open($this->createStreamMode('r+'));
        } catch (\RuntimeException $e) {
            return false;
        }

        return $stream->stat();
    }

    /**
     * @param $path
     * @throws \InvalidArgumentException
     * @return StreamInterface
     */
    private function createStream($path)
    {
        $parts = array_merge(
            array(
                'scheme' => null,
                'host' => null,
                'path' => null,
                'query' => null,
                'fragment' => null,
            ),
            parse_url($path) ? : array()
        );

        $domain = $parts['host'];
        $key = substr($parts['path'], 1);
        if (empty($key)) {
            $key = '/';
        }

        if (null !== $parts['query']) {
            $key .= '?' . $parts['query'];
        }

        if (null !== $parts['fragment']) {
            $key .= '#' . $parts['fragment'];
        }

        if (empty($domain)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The specified path (%s) is invalid.',
                    $path
                )
            );
        }

        return StreamWrapperManager::getFilesystemMap()->get($domain)->createStream($key);
    }

    /**
     * @param $mode
     * @return StreamMode
     */
    private function createStreamMode($mode)
    {
        return new StreamMode($mode);
    }
}
