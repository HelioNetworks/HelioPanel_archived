<?php

namespace HelioNetworks\HelioHostAccountBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('helio_networks_helio_host_account');

        $rootNode
            ->children()
                ->scalarNode('server')
                    ->cannotBeOverwritten()
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()
                ->scalarNode('username')
                    ->cannotBeOverwritten()
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()
                ->scalarNode('password')
                    ->cannotBeOverwritten()
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()
                ->end();

        return $treeBuilder;
    }
}