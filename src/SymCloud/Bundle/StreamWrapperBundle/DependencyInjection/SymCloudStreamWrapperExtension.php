<?php
/*
 * This file is part of the Sulu CMS.
 *
 * (c) MASSIVE ART WebServices GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SymCloud\Bundle\StreamWrapperBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SymCloudStreamWrapperExtension extends Extension
{

    private $factories = null;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();

        // first assemble the adapter factories
        $factoryConfig = new FactoryConfiguration();
        $config = $processor->processConfiguration($factoryConfig, $configs);
        $factories = $this->createAdapterFactories($config, $container);

        // then normalize the configs
        $mainConfig = new MainConfiguration($factories);
        $config = $processor->processConfiguration($mainConfig, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $adapters = array();
        foreach ($config['adapters'] as $name => $adapter) {
            $adapters[$name] = $this->createAdapter($name, $adapter, $container, $factories);
        }

        $map = array();
        foreach ($config['filesystems'] as $name => $filesystem) {
            $map[$name] = $this->createFilesystem($name, $filesystem, $container, $adapters);
        }

        $container->getDefinition('symcloud_streamwrapper.mount_manager')
            ->replaceArgument(0, $map);

        if (isset($config['stream_wrapper'])) {
            $container->setParameter(
                'symcloud_streamwrapper.stream_wrapper.protocol',
                $config['stream_wrapper']['protocol']
            );

            $container->setParameter(
                'symcloud_streamwrapper.stream_wrapper.filesystems',
                $config['stream_wrapper']['filesystems']
            );
        }
    }

    /**
     * @param $name
     * @param array $config
     * @param ContainerBuilder $container
     * @param array $factories
     * @return string
     * @throws \LogicException
     */
    private function createAdapter($name, array $config, ContainerBuilder $container, array $factories)
    {
        $adapter = null;
        foreach ($config as $key => $adapter) {
            if (array_key_exists($key, $factories)) {
                $id = sprintf('symcloud_streamwrapper.%s_adapter', $name);
                $factories[$key]->create($container, $id, $adapter);

                return $id;
            }
        }

        throw new \LogicException(sprintf('The adapter \'%s\' is not configured.', $name));
    }

    /**
     * @param $name
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $adapters
     * @throws \LogicException
     * @return Reference a reference to the created filesystem
     */
    private function createFilesystem($name, array $config, ContainerBuilder $container, array $adapters)
    {
        if (!array_key_exists($config['adapter'], $adapters)) {
            throw new \LogicException(sprintf('The adapter \'%s\' is not defined.', $config['adapter']));
        }

        $adapter = $adapters[$config['adapter']];
        $id = sprintf('symcloud_streamwrapper.%s_filesystem', str_replace('/', '', $name));

        $container
            ->setDefinition($id, new DefinitionDecorator('symcloud_streamwrapper.filesystem'))
            ->replaceArgument(0, new Reference($adapter));

        if (!empty($config['alias'])) {
            $container->getDefinition($id)->setPublic(false);
            $container->setAlias($config['alias'], $id);
        }

        return new Reference($id);
    }

    /**
     * Creates the adapter factories
     *
     * @param  array $config
     * @param  ContainerBuilder $container
     * @return array
     */
    private function createAdapterFactories($config, ContainerBuilder $container)
    {
        if (null !== $this->factories) {
            return $this->factories;
        }

        // load bundled adapter factories
        $tempContainer = new ContainerBuilder();
        $parameterBag = $container->getParameterBag();
        $loader = new XmlFileLoader($tempContainer, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('adapter_factories.xml');

        // load user-created adapter factories
        foreach ($config['factories'] as $factory) {
            $loader->load($parameterBag->resolveValue($factory));
        }

        $services = $tempContainer->findTaggedServiceIds('symcloud_streamwrapper.adapter.factory');
        $factories = array();
        foreach (array_keys($services) as $id) {
            $factory = $tempContainer->get($id);
            $factories[str_replace('-', '_', $factory->getKey())] = $factory;
        }

        return $this->factories = $factories;
    }
}
