<?php

namespace HelioNetworks\FileManagerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HelioNetworksFileManagerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

    }

    public function configureFilesystemAdapter(array $configs, ContainerBuilder $container)
    {
        // add the default configuration for the local filesystem
        if ($container->hasDefinition('helio_networks_file_manager.adapter.filesystem.local') && isset($config['filesystem']['helio_networks_file_manager.adapter.filesystem.local'])) {
            $definition = $container->getDefinition('helio_networks_file_manager.adapter.filesystem.local');
            $configuration =  $config['filesystem']['helio_networks_file_manager.adapter.filesystem.local'];
            $definition->addArgument($configuration['directory']);
            $definition->addArgument($configuration['create']);
        }

        // add the default configuration for the FTP filesystem
        if ($container->hasDefinition('helio_networks_file_manager.adapter.filesystem.ftp') && isset($config['filesystem']['helio_networks_file_manager.adapter.filesystem.ftp'])) {
            $definition = $container->getDefinition('helio_networks_file_manager.adapter.filesystem.ftp');
            $configuration =  $config['filesystem']['helio_networks_file_manager.adapter.filesystem.ftp'];
            $definition->addArgument($configuration['directory']);
            $definition->addArgument($configuration['username']);
            $definition->addArgument($configuration['password']);
            $definition->addArgument($configuration['port']);
            $definition->addArgument($configuration['passive']);
            $definition->addArgument($configuration['create']);
        }
    }
}