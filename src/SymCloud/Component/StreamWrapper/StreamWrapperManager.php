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

final class StreamWrapperManager
{
    /**
     * @var MountManagerInterface
     */
    private static $mountManager;

    /**
     * defines the mount manager
     * @param MountManagerInterface $mountManager
     */
    public static function setFilesystemMap(MountManagerInterface $mountManager)
    {
        static::$mountManager = $mountManager;
    }

    /**
     * returns the mount manager
     * @return MountManagerInterface $map
     */
    public static function getFilesystemMap()
    {
        if (null === static::$mountManager) {
            static::$mountManager = new MountManager();
        }

        return static::$mountManager;
    }

    /**
     * registers the stream wrapper to handle the specified scheme
     * @param string $scheme default is data
     * @param string $className
     * @throws \RuntimeException
     */
    public static function register($scheme = 'data', $className = 'SymCloud\Component\StreamWrapper\StreamWrapper')
    {
        static::streamWrapperUnregister($scheme);

        if (!static::streamWrapperRegister($scheme, $className)) {
            throw new \RuntimeException(
                sprintf(
                    'Could not register stream wrapper class %s for scheme %s.',
                    $className,
                    $scheme
                )
            );
        }
    }

    /**
     * unregister the stream wrapper for given scheme
     * @param string $scheme protocol scheme
     * @return bool
     */
    protected static function streamWrapperUnregister($scheme)
    {
        return @stream_wrapper_unregister($scheme);
    }

    /**
     * unregister a new stream wrapper for given scheme
     * @param string $scheme protocol scheme
     * @param string $className name of stream wrapper class
     * @return bool
     */
    protected static function streamWrapperRegister($scheme, $className)
    {
        return @stream_wrapper_register($scheme, $className);
    }
} 
