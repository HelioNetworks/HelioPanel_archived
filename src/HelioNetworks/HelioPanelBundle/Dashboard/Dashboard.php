<?php

namespace HelioNetworks\HelioPanelBundle\Dashboard;

class Dashboard
{
    protected $icons;

    public function addIcon(IconInterface $icon)
    {
        $this->icons[] = $icon;
    }

    public function getIcons()
    {
        return $this->icons;
    }
}
