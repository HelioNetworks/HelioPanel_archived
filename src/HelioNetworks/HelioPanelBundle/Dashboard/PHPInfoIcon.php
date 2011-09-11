<?php

namespace HelioNetworks\HelioPanelBundle\Dashboard;

class PHPInfoIcon implements IconInterface
{
	public function getRoute()
	{
		return 'heliopanel_phpinfo';
	}

	public function getImage()
	{
		return 'bundles/helionetworksheliopanel/images/phpbutton.png';
	}
}