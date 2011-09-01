<?php

namespace HelioNetworks\FileManagerBundle\Hook;

use HelioNetworks\HelioPanelBundle\Hook\HookSectionInterface;

class CreateDirectorySection implements HookSectionInterface
{
	public function getName()
	{
		return 'createDirectory($source)';
	}

	public function getCode()
	{
		return <<<'PHP'
return mkdir(dirname(__DIR__).'/'.$source);
PHP;
	}
}