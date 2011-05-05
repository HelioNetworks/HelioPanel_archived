<?php

namespace HelioNetworks\FileManagerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class HelioNetworksFileManagerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->setParameter('helionetworks_filemanager.filesystem.base_directory', '/home1/area52/public_html/test_dir/');

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

    }
}