<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SymCloud\Component\StreamWrapper\Adapter;

use SymCloud\Component\StreamWrapper\Stream\StreamInterface;

interface StreamFactoryInterface
{
    /**
     * creates a new stream instance of the specified file
     * @param string $key
     * @return StreamInterface
     */
    public function createStream($key);
} 
