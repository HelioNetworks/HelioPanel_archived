<?php

namespace HelioNetworks\HelioPanelBundle;

use HelioNetworks\HelioPanelBundle\DependencyInjection\Compiler\HookManagerPass;
use HelioNetworks\HelioPanelBundle\DependencyInjection\Compiler\DashboardPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HelioNetworksHelioPanelBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new HookManagerPass());
        $container->addCompilerPass(new DashboardPass());
    }
}
