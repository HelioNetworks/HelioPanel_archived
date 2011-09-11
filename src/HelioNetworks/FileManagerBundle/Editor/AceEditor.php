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
		return array();
	}
}