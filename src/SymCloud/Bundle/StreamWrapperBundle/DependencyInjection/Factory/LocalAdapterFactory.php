<?php

namespace SymCloud\Bundle\StreamWrapperBundle\DependencyInjection\Factory;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

/**
 * Local adapter factory
 *
 * @author Antoine Hérault <antoine.herault@gmail.com>
 */
class LocalAdapterFactory implements AdapterFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $container
            ->setDefinition($id, new DefinitionDecorator('symcloud_streamwrapper.adapter.local'))
            ->replaceArgument(0, $config['directory'])
            ->replaceArgument(1, $config['create'])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getKey()
    {
        return 'local';
    }

    /**
     * {@inheritDoc}
     */
    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('directory')->isRequired()->end()
                ->booleanNode('create')->defaultTrue()->end()
            ->end()
        ;
    }
}
