<?php

namespace HelioNetworks\HelioPanelBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class DashboardPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('heliopanel.dashboard')) {
            return;
        }

        $manager = $container->getDefinition('heliopanel.dashboard');
        $icons = array();

        foreach ($container->findTaggedServiceIds('heliopanel.dashboard.icon') as $id => $tags) {
            $icons[] = $id;
        }

        foreach ($icons as $id) {
            $manager->addMethodCall('addIcon', array(new Reference($id)));
        }
    }
}