<?php

namespace HelioNetworks\SQLBuddyBundle\Dashboard;

use HelioNetworks\HelioPanelBundle\Dashboard\IconInterface;

class SQLBuddyIcon implements IconInterface
{
    public function getImage()
    {
        return 'bundles/helionetworkssqlbuddy/images/icon.png';
    }

    public function getRoute()
    {
        return 'sqlbuddy_index';
    }
}
