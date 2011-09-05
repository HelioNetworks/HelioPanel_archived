<?php

namespace HelioNetworks\SQLBuddyBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use HelioNetworks\HelioPanelBundle\Controller\HelioPanelAbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends HelioPanelAbstractController
{
    /**
     * @Route("/sqlbuddy/", name="sqlbuddy_index")
     */
    public function indexAction()
    {
    	$account = $this->getActiveAccount();

    	$_SESSION['sqlbuddy_username'] = $account->getUsername();
    	$_SESSION['sqlbuddy_password'] = $account->getPassword();
    	$_SESSION['sqlbuddy_host'] = parse_url($this->getHook()->getUrl(), PHP_URL_HOST);

    	return new RedirectResponse('/sqlbuddy/index.php');
    }
}
