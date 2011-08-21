<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use HelioNetworks\HelioPanelBundle\Request;
use HelioNetworks\HelioPanelBundle\Form\Type\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use HelioNetworks\HelioPanelBundle\Entity\Account;

class AccountController extends Controller
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
        			$account->setHookfile($hookUrl);

        			$user = $this->get('security.context')->getToken()->getUser();
        			$user->addAccounts($account);

        			$em = $this->getDoctrine()->getEntityManager();
        			$em->persist($account);
        			$em->persist($user);
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
     * @Route("/account/{id}/setActive", name="set_active_account")
     */
    public function setActiveAction($id)
    {
    	$account = $this->getDoctrine()
    		->getRepository('HelioNetworksHelioPanelBundle:Account')
    		->findOneById($id);

    	if ($account) {
    		$this->get('security.context')
    			->getToken()
    			->getUser()
    			->setActiveAccount($account);
    	}

    	return new RedirectResponse('/');
    }

    //TODO: Add deleteAction
}
