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

interface StreamInterface
{
    public function cast($castAs);

    public function close();

    public function eof();

    public function flush();

    public function read($count);

    public function seek($offset, $whence);

    public function stat();

    public function tell();

    public function write($data);

    public function open(StreamMode $mode);

    public function unlink();
}
