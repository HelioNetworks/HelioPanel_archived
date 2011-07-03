<?php

namespace HelioNetworks\HelioHostAccountBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HelioNetworksHelioHostAccountExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processor = new Processor();

        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('hh_account.server', $config['server']);
        $container->setParameter('hh_account.username', $config['username']);
        $container->setParameter('hh_account.password', $config['password']);
        $container->setParameter('hh_account.database', $config['database']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}