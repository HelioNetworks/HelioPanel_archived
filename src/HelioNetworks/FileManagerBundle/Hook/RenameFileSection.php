<?php

namespace HelioNetworks\FileManagerBundle\Hook;

use HelioNetworks\HelioPanelBundle\Hook\HookSectionInterface;

class RenameFileSection implements HookSectionInterface
{
	public function getName()
	{
		return 'renameFile($source, $dest)';
	}

	public function getCode()
	{
		return <<<'PHP'
return rename(dirname(__DIR__).'/'.$source, dirname(__DIR__).'/'.$dest);
PHP;
	}
}