<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

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
        return $this->getActiveAccount()
            ->getHook();
    }

    protected function installHook(Account $account)
    {
        $auth = mt_rand();
        $hookfile = $this->get('heliopanel.hook_manager')->getCode($auth);

        $request = new Request('http://heliopanel.heliohost.org/install/autoinstall.php');
        $request->setData(array(
                'username' => $account->getUsername(),
                'password' => $account->getPassword(),
                'hookfile' => $hookfile,
        ));
        $request->setMethod('POST');

        $url = $request->send()->getData();
        if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url)) {
            $hook = new Hook();
            $hook->setAuth($auth);
            $hook->setUrl($url);

            $account->setHook($hook);
            $this->getDoctrine()->getEntityManager()->persist($hook);

            return $account;
        }
    }

    /**
     * @return Account
     */
    protected function getActiveAccount()
    {
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

        $this->setActiveAccount($account);

        return $account;
    }

    protected function setActiveAccount(Account $account)
    {
        if ($account->getUser() !== $this->getUser()) {

            throw new \UnexpectedValueException('Account is not owned by current user.');
        }


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
