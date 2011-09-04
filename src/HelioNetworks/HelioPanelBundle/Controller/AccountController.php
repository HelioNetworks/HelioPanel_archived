<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

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

    protected function installHook(Account $account)
    {
    	$auth = mt_rand();
    	$hookfile = $this->get('heliopanel.hook_manager')->getCode($auth);

    	$account->setHookfileauth($auth);

    	$request = new Request('http://heliopanel.heliohost.org/install/autoinstall.php');
    	$request->setData(array(
    		'username' => $account->getUsername(),
    	    'password' => $account->getPassword(),
    	    'hookfile' => $hookfile,
    	));
    	$request->setMethod('POST');

    	$hookUrl = $request->send()->getData();
    	if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $hookUrl)) {
    		$account->setHookfile($hookUrl);

    		return $account;
    	}
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
    		} else {
    			$this->get('session')->setFlash('error', 'The active account was not updated');
    		}
    		return new RedirectResponse($this->generateUrl('heliopanel_index'));
    	}

    	return array('form' => $form->createView(), 'activeAccount' => $this->getActiveAccount());
    }

    //TODO: Add deleteAction
}
