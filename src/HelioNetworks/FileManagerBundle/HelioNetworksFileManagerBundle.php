<?php

namespace HelioNetworks\FileManagerBundle;

use HelioNetworks\FileManagerBundle\DependencyInjection\Compiler\EditorPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HelioNetworksFileManagerBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new EditorPass());
	}
}