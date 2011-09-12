<?php

namespace HelioNetworks\HelioPanelBundle\Dashboard;

class HelpIcon implements IconInterface
{
    public function getRoute()
    {
        return 'heliopanel_help';
    }

    public function getImage()
    {
        return 'bundles/helionetworksheliopanel/images/helpbutton.png';
    }
}
