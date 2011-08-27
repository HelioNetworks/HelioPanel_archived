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
        return array();
    }

    /**
     * Create a new user based from a cPanel account.
     *
     * @Route("/createFromAccount", name="heliopanel_create_from_account")
     * @Template()
     */
    public function createFromAccountAction()
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
    				$user = new User();
    				$user->setPlainPassword($account->getPassword());
    				$user->setUsername($account->getUsername());

    				$userManager = $this->get('fos_user.user_manager');
    				$userManager->updateUser($user);

    				$em->persist($user);

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
}
