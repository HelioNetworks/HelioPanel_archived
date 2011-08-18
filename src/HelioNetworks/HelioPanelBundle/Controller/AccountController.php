<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use HelioNetworks\HelioPanelBundle\Request;
use HelioNetworks\HelioPanelBundle\Form\Type\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    /**
     * Adds an account to the logged in user.
     */
    public function addAction()
    {
        $account = new Account();
        $form = $this->createForm(new AccountType(), $account);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
        	$form->bindRequest($request);
        	if ($form->isValid()) {
        		$auth = base_convert(mt_rand(0x1D39D3E06400000, 0x41C21CB8E0FFFFFF), 10, 36);
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
        		} else {
        			$this->get('session')->setFlash('error', 'We could not verify that the account exists or that the password is correct.');
        		}
        	}
        }

        return array();
    }
}
