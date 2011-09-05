<?php

namespace HelioNetworks\HelioPanelBundle\Hook;

class GetPhpInfoSection implements HookSectionInterface
{
	public function getName()
	{
		return 'getPhpInfo()';
	}

	public function getCode()
	{
		return <<<'PHP'
ob_start();
phpinfo();

return ob_get_clean();
PHP;
	}
}