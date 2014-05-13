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

interface MountManagerInterface
{

    /**
     * returns an array of all the registered filesystems where the key is the
     * domain and the value the filesystem
     * @return array
     */
    public function all();

    /**
     * register the given filesystem for the specified domain
     * @param string $domain
     * @param FilesystemInterface $filesystem
     */
    public function set($domain, FilesystemInterface $filesystem);

    /**
     * indicates whether there is a filesystem registered for the specified domain
     * @param string $domain
     * @return Boolean
     */
    public function has($domain);

    /**
     * returns the filesystem registered for the specified domain
     * @param string $domain
     * @return FilesystemInterface
     */
    public function get($domain);

    /**
     * removes the filesystem registered for the specified domain
     * @param string $domain
     * @return void
     */
    public function remove($domain);

    /**
     * clears all the registered filesystems
     * @return void
     */
    public function clear();

    /**
     * returns analysed uri
     * @param string $uri
     * @return array keys: domain, key
     */
    public function analyse($uri);

    /**
     * returns array of children for given path
     * @param string $key
     * @param string $domain
     * @return array
     */
    public function getMountChildren($key, $domain);
}
