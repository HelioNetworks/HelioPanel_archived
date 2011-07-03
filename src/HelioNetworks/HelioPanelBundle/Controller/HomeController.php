<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller {

    /**
     * @Route("/", name="home")
     * @Template()
     */
    function homeAction() {

        return array();
    }
}