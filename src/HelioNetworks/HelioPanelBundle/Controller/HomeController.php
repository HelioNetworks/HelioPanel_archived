<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

    /**
     * Display welcome page
     */
    function homeAction() {

        return $this->render('HelioNetworksHelioPanelBundle:Home:home.html.twig');
    }
}