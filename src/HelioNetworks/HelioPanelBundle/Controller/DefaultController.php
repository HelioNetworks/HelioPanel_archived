<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use HelioNetworks\HelioPanelBundle\Entity\User;
use HelioNetworks\HelioPanelBundle\Form\Type\AccountType;
use HelioNetworks\HelioPanelBundle\Entity\Account;
use HelioNetworks\HelioPanelBundle\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends HelioPanelAbstractController
{
    /**
     * @Route("/", name="heliopanel_index")
     * @Template()
     */
    public function indexAction()
    {
        return array('dashboard' => $this->get('heliopanel.dashboard'));
    }

    /**
     * @Route("/phpinfo", name="heliopanel_phpinfo")
     */
    public function phpInfoAction()
    {
    	return new Response($this->getHook()->getPhpInfo());
    }

    /**
     * @Route("/help", name="heliopanel_help")
     */
    public function helpAction()
    {
    	return new RedirectResponse('http://heliopanel.heliohost.org/support.php');
    }
}
