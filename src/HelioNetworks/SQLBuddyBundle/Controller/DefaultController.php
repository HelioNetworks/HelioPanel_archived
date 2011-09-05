<?php

namespace HelioNetworks\SQLBuddyBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
	protected function getVendorDir()
	{
		return dirname($this->get('kernel')->getRootDir()).'/vendor';
	}

	protected function getSQLBuddyDir()
	{
		return $this->getVendorDir().'/sqlbuddy';
	}

    /**
     * @Route("/sqlbuddy/(.*)")
     */
    public function indexAction()
    {
    	ob_start();
    	require $this->getSQLBuddyDir().'/'.$file;

        return new Response(ob_get_clean());
    }
}
