<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use HelioNetworks\HelioPanelBundle\Exception\NoAccountsException;
use HelioNetworks\HelioPanelBundle\HTTP\Request;
use HelioNetworks\HelioPanelBundle\Entity\Hook;
use HelioNetworks\HelioPanelBundle\Entity\User;
use HelioNetworks\HelioPanelBundle\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class HelioPanelAbstractController extends Controller
{
    /**
     * @return User
     */
    protected function getUser()
    {
        return $this->get('security.context')
            ->getToken()
            ->getUser();
    }

    /**
     * @return Hook
     */
    protected function getHook()
    {
        $hook = $this->getActiveAccount()
            ->getHook();
        $hook->setLogger($this->get('logger'));

        return $hook;
    }

    protected function installHook(Account $account)
    {
    	//Get the HelioHost API
        $api = $this->get('heliohost.api');

        //Check the username/password combination
        if ($api->checkPassword($account->getUsername(), $account->getPassword())) {
        	$this->get('logger')->debug(print_r($this->get('heliohost.api'), true));

        	//Get the Entity Manager
        	$em = $this->getDoctrine()->getEntityManager();

        	//Remove the Hook (if applicable)
        	if ($hook = $account->getHook()) {
        		$hook->delete();
        		$em->remove($hook);
        	}

        	$this->get('logger')->debug(sprintf('Installing hook on account with ID %s.', $account->getId()));

        	//Get the HookFile code
        	$auth = mt_rand();
        	$hookfile = $this->get('heliopanel.hook_manager')->getCode($auth);

        	//Get the HelioHost account
        	$acct = $api->getAccount($account->getUsername());

        	//Calculate the filename
        	$filename = mt_rand().'.php';
        	$ftpPath = 'public_html/'.$filename;
        	$hookUrl = 'http://'.$acct->plan->server->host.'/'.$filename;

        	$api->storeFile(
        		$account->getUsername(), //Username
        		$account->getPassword(), //Password
        		$ftpPath, //Path on FTP
        		$hookfile //Contents of the file
        	);

        	$hook = new Hook();
        	$hook->setAuth($auth);
        	$hook->setUrl($hookUrl);

        	$account->setHook($hook);
        	$em->persist($hook);

        	return $account;
        }
    }

    /**
     * @return Account
     */
    protected function getActiveAccount()
    {
        $this->get('logger')->debug('Fetching active account...');

        if ($id = $this->get('session')->get('active_account_id')) {

            $account = $this->getDoctrine()
                ->getRepository('HelioNetworksHelioPanelBundle:Account')
                ->findOneById($id);

            if ($account) {

                return $account;
            }

        }

        $account = $this->getUser()
            ->getAccounts()
            ->first();

        if (!$account) {
        	 throw new NoAccountsException();
        }

        $this->setActiveAccount($account);

        $this->get('logger')->debug(sprintf('Got account with id %s.', $account->getId()));

        return $account;
    }

    protected function setActiveAccount(Account $account)
    {
        if ($account->getUser() !== $this->getUser()) {

            throw new \UnexpectedValueException('Account is not owned by current user.');
        }

        $this->get('logger')->debug(sprintf('Setting active account with id %s.', $account->getId()));

        $request = new Request($account->getHook()->getUrl());
        $request->setMethod('GET');
        $request->setData(array());

        if ($this->get('heliopanel.hook_manager')->getHash() !== $request->send()->getData()) {
            //Hook is not up to date
            $this->installHook($account);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($account);
            $em->flush();
        }

        $this->get('session')
            ->set('active_account_id', $account->getId());
    }
}
