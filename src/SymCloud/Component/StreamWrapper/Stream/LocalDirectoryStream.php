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
use SymCloud\Component\StreamWrapper\MountManager;
use SymCloud\Component\StreamWrapper\StreamWrapper;
use SymCloud\Component\StreamWrapper\StreamWrapperManager;
use SymCloud\Component\StreamWrapper\Util\Path;

class LocalDirectoryStream extends Stream
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
    public function close()
    {
        closedir($this->handle);

        $this->handle = null;
        $this->children = array();
        $this->position = 0;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($count = 0)
    {
        if (!$this->handle) {
            return false;
        }

        if (sizeof($this->children) === $this->position) {
            return false;
        }

        return $this->children[$this->position++];
    }

    /**
     * {@inheritdoc}
     */
    public function stat()
    {
        return stat($this->path);
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
        $this->children = array_unique(
            array_merge(
                scandir($this->path),
                StreamWrapperManager::getFilesystemMap()->getMountChildren($this->key, $this->domain)
            )
        );
        $this->position = 0;

        return true;
    }

    public function mkdir($name, $mode, $options)
    {
        mkdir(Path::normalize($this->path . '/' . $name), $mode, $options);
    }

}
