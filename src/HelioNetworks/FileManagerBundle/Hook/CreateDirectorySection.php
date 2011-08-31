<?php

namespace HelioNetworks\FileManagerBundle\Hook;

use HelioNetworks\HelioPanelBundle\Hook\HookSectionInterface;

class CreateDirectorySection extends HookSectionInterface
{
	public function getName()
	{
		return 'createDirectory($source)';
	}

	public function getSource()
	{
		return <<<'PHP'
return mkdir(dirname(__DIR__).'/'.$source);
PHP;
	}
}