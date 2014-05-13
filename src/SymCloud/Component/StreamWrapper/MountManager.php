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
use SymCloud\Component\StreamWrapper\Filesystem\VirtualFilesystem;
use SymCloud\Component\StreamWrapper\Util\Path;

class MountManager implements MountManagerInterface
{
    /**
     * @var array
     */
    private $filesystems = array();

    /**
     * @var FilesystemInterface[]
     */
    private $filesystemTree = array();

    function __construct($filesystems = array())
    {
        $this->filesystems = $filesystems;
    }

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
        $domainParts = explode('/', $domain);
        $domainPart = '';
        $array = & $this->filesystemTree;
        foreach ($domainParts as $part) {
            $domainPart = ltrim(Path::normalize($domainPart . '/' . $part), '/');

            if (!isset($array[$part])) {
                $array[$part] = array();
            }

            $array = & $array[$part];
        }
        $array['/'] = $filesystem;

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

    /**
     * {@inheritdoc}
     */
    public function getMountChildren($key, $domain)
    {
        $domainParts = explode('/', Path::normalize($domain . '/' . $key));
        $array = $this->filesystemTree;
        foreach ($domainParts as $part) {
            if ($part !== '') {
                if (!isset($array[$part])) {
                    $array = array();
                    break;
                }
                $array = $array[$part];
            }
        }

        $result = array_keys($array);
        $result = array_diff($result, array('/'));
        $result = array_values($result);
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function analyse($uri)
    {
        $parts = array_merge(
            array(
                'scheme' => null,
                'host' => null,
                'path' => null,
                'query' => null,
                'fragment' => null,
            ),
            parse_url($uri) ? : array()
        );

        $path = Path::normalize($parts['host'] . $parts['path']);

        $mountPoints = array_keys($this->filesystems);
        usort(
            $mountPoints,
            function ($a, $b) {
                return strlen($a) < strlen($b);
            }
        );
        foreach ($mountPoints as $mountPoint) {
            if (
                // starts with mountpoint
                strpos($path, $mountPoint) === 0 &&
                // len of mount point smaller as len of path
                strlen($mountPoint) <= strlen($path) &&
                (
                    // mountpoint don´t explode a part (the rest starts with a /)
                    strpos(str_replace($mountPoint, '', $path), '/') === 0 ||
                    // or mountpoint point to full path
                    $mountPoint === $path
                )
            ) {
                $domain = $mountPoint;
                break;
            }
        }

        if (empty($domain)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The specified path (%s) is invalid.',
                    $path
                )
            );
        }

        $key = ltrim(str_replace($domain, '', $path), '/');

        if (empty($key)) {
            $key = '/';
        }

        if (null !== $parts['query']) {
            $key .= '?' . $parts['query'];
        }

        if (null !== $parts['fragment']) {
            $key .= '#' . $parts['fragment'];
        }

        return array(
            'key' => $key,
            'domain' => $domain
        );
    }
} 
