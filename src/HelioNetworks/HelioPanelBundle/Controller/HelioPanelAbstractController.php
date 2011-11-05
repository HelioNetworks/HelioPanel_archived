<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use HelioNetworks\HelioPanelBundle\Job\InstallHookJob;
use Xaav\QueueBundle\Queue\QueueManager;
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

    protected function getServer()
    {
    	if ($account = $this->getActiveAccount()) {
    		return $account->getServer();
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

        $this->get('session')
            ->set('active_account_id', $account->getId());
    }

    /**
     * @return QueueManager
     */
    protected function getQueueManager()
    {
    	return $this->get('xaav.queue.manager');
    }
}
