<?php

namespace HelioNetworks\FileManagerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class EditorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('heliopanel.editor_manager')) {
            return;
        }

        $manager = $container->getDefinition('heliopanel.editor_manager');
        $editors = array();

        foreach ($container->findTaggedServiceIds('heliopanel.editor_manager.editor') as $id => $tags) {
            $editors[] = $id;
        }

        foreach ($editors as $id) {
            $manager->addMethodCall('addEditor', array(new Reference($id)));
        }
    }
}