<?php

namespace HelioNetworks\FileManagerBundle\Dashboard;

use HelioNetworks\HelioPanelBundle\Dashboard\IconInterface;

class FileManagerIcon implements IconInterface
{
	public function getImage()
	{
		return 'bundles/helionetworksfilemanager/images/filemanagerbutton.png';
	}

	public function getRoute()
	{
		return 'directory_list';
	}
}