<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SymCloud\Component\StreamWrapper\Filesystem;

use SymCloud\Component\StreamWrapper\Stream\MountPointStream;
use SymCloud\Component\StreamWrapper\Stream\StreamInterface;

class VirtualFilesystem implements FilesystemInterface
{
    /**
     * @var string
     */
    private $virtualPath;

    function __construct($virtualPath)
    {
        $this->virtualPath = $virtualPath;
    }

    /**
     * @param string $key
     * @param string $domain
     * @return StreamInterface
     */
    public function createStream($key, $domain)
    {
        return new MountPointStream($this->virtualPath, $key, $domain);
    }
}
