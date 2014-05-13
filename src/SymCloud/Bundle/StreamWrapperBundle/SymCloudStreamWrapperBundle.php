<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SymCloud\Bundle\StreamWrapperBundle;

use SymCloud\Component\StreamWrapper\StreamWrapperManager;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymCloudStreamWrapperBundle extends Bundle
{
    public function boot()
    {
        parent::boot();

        if (!$this->container->hasParameter('symcloud_streamwrapper.stream_wrapper.protocol')
            || !$this->container->hasParameter('symcloud_streamwrapper.stream_wrapper.filesystems')) {
            return;
        }

        StreamWrapperManager::register($this->container->getParameter('symcloud_streamwrapper.stream_wrapper.protocol'));
        $wrapperFsMap = StreamWrapperManager::getFilesystemMap();

        $fileSystems = $this->container->getParameter('symcloud_streamwrapper.stream_wrapper.filesystems');

        /*
         * If there are no filesystems configured to be wrapped,
         * all filesystems within the map will be wrapped.
         */
        if (empty($fileSystems)) {
            $mountManager = $this->container->get('symcloud_streamwrapper.mount_manager');
            $fileSystems =$mountManager->all();

            foreach ($fileSystems as $domain => $fileSystem) {
                $wrapperFsMap->set($domain, $fileSystem);
            }
        } else {
            foreach ($fileSystems as $domain => $fileSystem) {
                $wrapperFsMap->set($domain, $this->container->get('symcloud_streamwrapper.mount_manager')->get($fileSystem));
            }
        }
    }
} 
