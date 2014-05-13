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

abstract class Stream implements StreamInterface
{
    /**
     * {@inheritdoc}
     */
    public function cast($castAs)
    {
        throw new NotSupportedException(__CLASS__ . ': cast');
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        throw new NotSupportedException(__CLASS__ . ': close');
    }

    /**
     * {@inheritdoc}
     */
    public function eof()
    {
        throw new NotSupportedException(__CLASS__ . ': eof');
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        throw new NotSupportedException(__CLASS__ . ': flush');
    }

    /**
     * {@inheritdoc}
     */
    public function read($count = 0)
    {
        throw new NotSupportedException(__CLASS__ . ': read');
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence)
    {
        throw new NotSupportedException(__CLASS__ . ': seek');
    }

    /**
     * {@inheritdoc}
     */
    public function stat()
    {
        throw new NotSupportedException(__CLASS__ . ': stat');
    }

    /**
     * {@inheritdoc}
     */
    public function tell()
    {
        throw new NotSupportedException(__CLASS__ . ': tell');
    }

    /**
     * {@inheritdoc}
     */
    public function write($data)
    {
        throw new NotSupportedException(__CLASS__ . ': write');
    }

    /**
     * {@inheritdoc}
     */
    public function open(StreamMode $mode = null)
    {
        throw new NotSupportedException(__CLASS__ . ': open');
    }

    /**
     * {@inheritdoc}
     */
    public function unlink()
    {
        throw new NotSupportedException(__CLASS__ . ': unlink');
    }

    /**
     * {@inheritdoc}
     */
    public function mkdir($name, $mode, $options)
    {
        throw new NotSupportedException(__CLASS__ . ': mkdir');
    }
}
