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

use SymCloud\Component\StreamWrapper\Adapter\AdapterInterface;
use SymCloud\Component\StreamWrapper\Adapter\StreamFactoryInterface;
use SymCloud\Component\StreamWrapper\Stream\MemoryStream;
use SymCloud\Component\StreamWrapper\Stream\StreamInterface;
use SymCloud\Component\StreamWrapper\StreamWrapperManager;
use SymCloud\Component\StreamWrapper\Util\Path;

class Filesystem implements FilesystemInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    function __construct($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param string $key
     * @param string $domain
     * @return StreamInterface
     */
    public function createStream($key, $domain)
    {
        $completePath = Path::normalize($domain . '/' . $key);
        if ($key !== '/' && StreamWrapperManager::getFilesystemMap()->has($completePath)) {
            return StreamWrapperManager::getFilesystemMap()->get($completePath)->createStream('/', $completePath);
        } else {
            if ($this->adapter instanceof StreamFactoryInterface) {
                return $this->adapter->createStream($key, $domain);
            }
            return new MemoryStream($this, $key);
        }
    }
}
