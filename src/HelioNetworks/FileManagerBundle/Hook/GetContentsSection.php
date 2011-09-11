<?php

namespace HelioNetworks\FileManagerBundle\Hook;

use HelioNetworks\HelioPanelBundle\Hook\HookSectionInterface;

class GetContentsSection implements HookSectionInterface
{
	public function getName()
	{
		return 'getContents($source)';
	}

	public function getCode()
	{
		return <<<'PHP'
return file_get_contents(dirname(__DIR__).'/'.$source);
PHP;
	}
}