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

use DateTime;
use SymCloud\Component\StreamWrapper\Exception\NotSupportedException;
use SymCloud\Component\StreamWrapper\StreamWrapperManager;

class MountPointStream implements StreamInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $domain;

    /**
     * @var array
     */
    private $children;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var resource
     */
    private $handle;

    function __construct($path, $key, $domain)
    {
        $this->path = $path;
        $this->key = $key;
        $this->domain = $domain;
    }

    /**
     * {@inheritdoc}
     */
    public function cast($castAs)
    {
        throw new NotSupportedException('cast');
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->children = array();
        $this->position = 0;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function eof()
    {
        throw new NotSupportedException('eof');
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        throw new NotSupportedException('flush');
    }

    /**
     * {@inheritdoc}
     */
    public function read($count = 0)
    {
        if (sizeof($this->children) === $this->position) {
            return false;
        }

        return $this->children[$this->position++];
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence)
    {
        throw new NotSupportedException('seek');
    }

    /**
     * {@inheritdoc}
     */
    public function stat()
    {
        $time = time();

        $stats = array(
            'dev'   => 1,
            'ino'   => 0,
            'mode'  => 16877,
            'nlink' => 1,
            'uid'   => 0,
            'gid'   => 0,
            'rdev'  => 0,
            'size'  => 0,
            'atime' => $time,
            'mtime' => $time,
            'ctime' => $time,
            'blksize' => -1,
            'blocks'  => -1,
        );

        return array_merge(array_values($stats), $stats);
    }

    /**
     * {@inheritdoc}
     */
    public function tell()
    {
        throw new NotSupportedException('tell');
    }

    /**
     * {@inheritdoc}
     */
    public function write($data)
    {
        throw new NotSupportedException('write');
    }

    /**
     * {@inheritdoc}
     */
    public function open(StreamMode $mode = null)
    {
        $this->children = StreamWrapperManager::getFilesystemMap()->getMountChildren($this->domain, $this->key);
        $this->position = 0;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function unlink()
    {
        throw new NotSupportedException('unlink');
    }
}
