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

interface StreamWrapperInterface
{
    /**
     * constructor
     *
     */
    public function __construct();

    /**
     * Enter description here...
     *
     * @return bool
     */
    public function dir_closedir();

    /**
     * Enter description here...
     *
     * @param string $path
     * @param int $options
     * @return bool
     */
    public function dir_opendir($path, $options);

    /**
     * Enter description here...
     *
     * @return string
     */
    public function dir_readdir();

    /**
     * Enter description here...
     *
     * @return bool
     */
    public function dir_rewinddir();

    /**
     * Enter description here...
     *
     * @param string $path
     * @param int $mode
     * @param int $options
     * @return bool
     */
    public function mkdir($path, $mode, $options);

    /**
     * Enter description here...
     *
     * @param string $path_from
     * @param string $path_to
     * @return bool
     */
    public function rename($path_from, $path_to);

    /**
     * Enter description here...
     *
     * @param string $path
     * @param int $options
     * @return bool
     */
    public function rmdir($path, $options);

    /**
     * Enter description here...
     *
     * @param int $castAs
     * @return resource
     */
    public function stream_cast($castAs);

    /**
     * Enter description here...
     *
     */
    public function stream_close();

    /**
     * Enter description here...
     *
     * @return bool
     */
    public function stream_eof();

    /**
     * Enter description here...
     *
     * @return bool
     */
    public function stream_flush();

    /**
     * Enter description here...
     *
     * @param mode $operation
     * @return bool
     */
    public function stream_lock($operation);

    /**
     * Enter description here...
     *
     * @param string $path
     * @param string $mode
     * @param int $options
     * @param string &$opened_path
     * @return bool
     */
    public function stream_open($path, $mode, $options, &$opened_path);

    /**
     * Enter description here...
     *
     * @param int $count
     * @return string
     */
    public function stream_read($count);

    /**
     * Enter description here...
     *
     * @param int $offset
     * @param int $whence = SEEK_SET
     * @return bool
     */
    public function stream_seek($offset, $whence = SEEK_SET);

    /**
     * Enter description here...
     *
     * @param int $option
     * @param int $arg1
     * @param int $arg2
     * @return bool
     */
    public function stream_set_option($option, $arg1, $arg2);

    /**
     * Enter description here...
     *
     * @return array
     */
    public function stream_stat();

    /**
     * Enter description here...
     *
     * @return int
     */
    public function stream_tell();

    /**
     * Enter description here...
     *
     * @param string $data
     * @return int
     */
    public function stream_write($data);

    /**
     * Enter description here...
     *
     * @param string $path
     * @return bool
     */
    public function unlink($path);

    /**
     * Enter description here...
     *
     * @param string $path
     * @param int $flags
     * @return array
     */
    public function url_stat($path, $flags);
}
