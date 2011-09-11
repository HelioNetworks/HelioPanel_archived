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

    	$_SESSION['DefaultUser'] = $account->getUsername();
    	$_SESSION['DefaultPassword'] = $account->getPassword();
    	$_SESSION['DefaultHost'] = parse_url($this->getHook()->getUrl(), PHP_URL_HOST);

    	return new RedirectResponse('/sqlbuddy/index.php');
    }
}
