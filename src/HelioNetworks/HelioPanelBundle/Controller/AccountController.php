<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use HelioNetworks\HelioPanelBundle\Entity\Hook;
use HelioNetworks\HelioPanelBundle\HTTP\Request;
use HelioNetworks\HelioPanelBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HelioNetworks\HelioPanelBundle\Form\Type\SetActiveAccountRequestType;
use HelioNetworks\HelioPanelBundle\Form\Model\SetActiveAccountRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use HelioNetworks\HelioPanelBundle\Form\Type\AccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use HelioNetworks\HelioPanelBundle\Entity\Account;

class AccountController extends HelioPanelAbstractController
{
	/**
	 * Create a new user based from a cPanel account.
	 *
	 * @Route("/account/createUser", name="account_create_user")
	 * @Template()
	 */
    public function createUserAction() {
    	$account = new Account();
    	$form = $this->createForm(new AccountType(), $account);

    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		$form->bindRequest($request);
    		if ($form->isValid()) {
    			if ($this->installHook($account)) {
    				$user = new User();
    				$user->setPlainPassword($account->getPassword());
    				$user->setUsername($account->getUsername());
    				$user->setEmail(uniqid().'@heliohost.org');
    				$user->setEnabled(true);

    				$this->get('fos_user.user_manager')
    					->updateUser($user);
    				$account->setUser($user);

   					$em = $this->getDoctrine()->getEntityManager();
    				$em->persist($user);
    				$em->persist($account);
    				$em->flush();

    				//TODO: Log user in

    				$this->get('session')->setFlash('success', 'You may now login with your existing cPanel creditentials.');

    				return new RedirectResponse('/');
    			} else {
    					$this->get('session')->setFlash('error', 'We could not verify that the account exists or that the password is correct.');
    			}
    		}
    	}

    	return array('form' => $form->createView());
    }

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
        		 if ($this->installHook($account)) {
        			$account->setUser($this->getUser());

        			$em = $this->getDoctrine()->getEntityManager();
        			$em->persist($account);
        			$em->flush();

        			$this->get('session')->setFlash('success', 'The account was added successfully!');

        			return new RedirectResponse($this->generateUrl('heliopanel_index'));
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
    		'accounts' => $this->getUser()->getAccounts()->toArray(),
    		'current_account' => $this->getActiveAccount(),
    	));

    	$request = $this->getRequest();
    	if ($request->getMethod() == 'POST') {
    		$form->bindRequest($request);
    		if ($form->isValid()) {
    			$this->setActiveAccount($accountRequest->getActiveAccount());
    		} else {
    			$this->get('session')->setFlash('error', 'The active account was not updated');
    		}

    		if(!$url = $this->getRequest()->server->get('HTTP_REFERER')) {
    			$url = $this->generateUrl('heliopanel_index');
    		}

    		return new RedirectResponse($url);
    	}

    	return array('form' => $form->createView());
    }

    //TODO: Add deleteAction
}
