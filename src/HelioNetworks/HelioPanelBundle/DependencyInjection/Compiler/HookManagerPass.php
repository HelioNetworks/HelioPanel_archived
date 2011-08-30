<?php

namespace HelioNetworks\HelioPanelBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class HookManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('heliopanel.hook_manager')) {
            return;
        }

        $manager = $container->getDefinition('heliopanel.hook_manager');
        $sections = array();

        foreach ($container->findTaggedServiceIds('heliopanel.hook_manager') as $id => $tags) {
            $sections[] = $id;
        }

        foreach ($sections as $id) {
            $manager->addMethodCall('addSection', array(new Reference($id)));
        }
    }
}