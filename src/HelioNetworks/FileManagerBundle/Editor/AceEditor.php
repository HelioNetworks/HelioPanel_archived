<?php

namespace HelioNetworks\FileManagerBundle\Editor;

class AceEditor implements EditorInterface
{
	public function getName()
	{
		return 'Ace Code Editor';
	}

	public function getAssets()
	{
		return array(
			'bundles/helionetworksfilemanager/js/ace/src/ace.js',
			'bundles/helionetworksfilemanager/js/ace/src/theme-twilight.js',
			'bundles/helionetworksfilemanager/js/ace/src/mode-php.js',
			'bundles/helionetworksfilemanager/js/ace.js',
		);
	}
}