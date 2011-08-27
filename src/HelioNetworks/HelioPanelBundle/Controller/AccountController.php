<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HelioNetworks\HelioPanelBundle\Form\Type\SetActiveAccountRequestType;
use HelioNetworks\HelioPanelBundle\Form\Model\SetActiveAccountRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use HelioNetworks\HelioPanelBundle\Request;
use HelioNetworks\HelioPanelBundle\Form\Type\AccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use HelioNetworks\HelioPanelBundle\Entity\Account;

class AccountController extends HelioPanelAbstractController
{
    /**
     * Adds an account to the logged in user.
     *
     * @Route("/account/add", name="account_add")
     * @Template()
     */
    public function addAction()
    {
        $account = new Account();
        $form = $this->createForm(new AccountType(), $account);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
        	$form->bindRequest($request);
        	if ($form->isValid()) {
        		$auth = mt_rand();
        		$hookfile = file_get_contents(__DIR__.'/../../../../web/hook.php');
        		$hookfile = str_replace('%authKey%', $auth, $hookfile);

        		$account->setHookfileauth($auth);

        		$requestData = array(
        			'username' => $account->getUsername(),
        			'password' => $account->getPassword(),
        			'hookfile' => $hookfile,
        		);

        		$postRequest = new Request('http://heliopanel.heliohost.org/install/autoinstall.php');
        		$postRequest->setData($requestData);
        		$hookUrl = $postRequest->send();

        		if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $hookUrl)) {
        			$user = $this->get('security.context')->getToken()->getUser();

        			$account->setHookfile($hookUrl);
        			$account->setUser($user);

        			$em = $this->getDoctrine()->getEntityManager();
        			$em->persist($account);
        			$em->flush();

        			$this->get('session')->setFlash('success', 'The account was added successfully!');

        			return new RedirectResponse('/');
        		} else {
        			$this->get('session')->setFlash('error', 'We could not verify that the account exists or that the password is correct.');
        		}
        	}
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/account/setActive", name="account_set_active")
     * @Method({"POST"})
     * @Template()
     */
    public function setActiveAction()
    {
    	$accountRequest = new SetActiveAccountRequest();
    	$form = $this->createForm(new SetActiveAccountRequestType(), $accountRequest, array(
    		'user' => $this->getUser(),
    	));

    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		$form->bindRequest($request);
    		if ($form->isValid()) {
    			$this->setActiveAccount($accountRequest->getActiveAccount());
    		}

    		return new RedirectResponse($this->generateUrl('heliopanel_index'));
    	}

    	return array('form' => $form->createView());
    }

    //TODO: Add deleteAction
}
