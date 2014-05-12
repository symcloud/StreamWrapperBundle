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

use SymCloud\Component\StreamWrapper\Filesystem\FilesystemInterface;

class MountManager implements MountManagerInterface
{
    private $filesystems = array();

    /**
     * returns an array of all the registered filesystems where the key is the
     * domain and the value the filesystem
     * @return array
     */
    public function all()
    {
        return $this->filesystems;
    }

    /**
     * register the given filesystem for the specified domain
     * @param string $domain
     * @param FilesystemInterface $filesystem
     */
    public function set($domain, FilesystemInterface $filesystem)
    {
        $this->filesystems[$domain] = $filesystem;
    }

    /**
     * indicates whether there is a filesystem registered for the specified domain
     * @param string $domain
     * @return Boolean
     */
    public function has($domain)
    {
        return isset($this->filesystems[$domain]);
    }

    /**
     * returns the filesystem registered for the specified domain
     * @param string $domain
     * @return FilesystemInterface
     */
    public function get($domain)
    {
        return $this->filesystems[$domain];
    }

    /**
     * removes the filesystem registered for the specified domain
     * @param string $domain
     * @return void
     */
    public function remove($domain)
    {
        unset($this->filesystems[$domain]);
    }

    /**
     * clears all the registered filesystems
     * @return void
     */
    public function clear()
    {
        $this->filesystems = array();
    }
} 
