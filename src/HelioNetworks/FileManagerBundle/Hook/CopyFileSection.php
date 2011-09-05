<?php

namespace HelioNetworks\FileManagerBundle\Hook;

use HelioNetworks\HelioPanelBundle\Hook\HookSectionInterface;

class CopyFileSection implements HookSectionInterface
{
	public function getName()
	{
		return 'copyFile($source, $dest)';
	}

	public function getCode()
	{
		return <<<'PHP'
return copy(dirname(__DIR__).'/'.$source, dirname(__DIR__).'/'.$dest);
PHP;
	}
}