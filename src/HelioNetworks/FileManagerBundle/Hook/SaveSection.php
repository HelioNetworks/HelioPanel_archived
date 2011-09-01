<?php

namespace HelioNetworks\FileManagerBundle\Hook;

use HelioNetworks\HelioPanelBundle\Hook\HookSectionInterface;

class SaveSection implements HookSectionInterface
{
	public function getName()
	{
		return 'save($source, $data)';
	}

	public function getCode()
	{
		return <<<'PHP'
return file_put_contents(dirname(__DIR__).'/'.$source, $data);
PHP;
	}
}