<?php

namespace HelioNetworks\FileManagerBundle\Hook;

use HelioNetworks\HelioPanelBundle\Hook\HookSectionInterface;

class CopySection implements HookSectionInterface
{
	public function getName()
	{
		return 'copy($source, $dest)';
	}

	public function getSource()
	{
		return <<<'PHP'
return copy(dirname(__DIR__).'/'.$source, dirname(__DIR__).'/'.$dest);
PHP;
	}
}