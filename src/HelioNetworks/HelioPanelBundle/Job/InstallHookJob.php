<?php

namespace HelioNetworks\HelioPanelBundle\Job;

use HelioNetworks\HelioPanelBundle\Entity\Account;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Xaav\QueueBundle\Queue\Job\JobInterface;

class InstallHookJob extends ContainerAware implements JobInterface, ContainerAwareInterface
{
	protected $accountId;

	public function __construct(Account $account)
	{
		$this->accountId = $account->getId();
	}

	public function process()
	{
        $api = $this->container->get('heliohost.api');
        $logger = $this->container->get('logger');
        $doctrine = $this->container->get('doctrine');
        $account = $doctrine->getRepository('HelioNetworksHelioPanelBundle:Account')->findById($this->accountId);

        //Check the username/password combination
        if ($api->checkPassword($account->getUsername(), $account->getPassword())) {
            $logger->debug(print_r($this->get('heliohost.api'), true));

            //Get the Entity Manager
            $em = $doctrine->getEntityManager();

            //Remove the Hook (if applicable)
            if ($hook = $account->getHook()) {
                $hook->delete();
                $em->remove($hook);
            }

            $logger->debug(sprintf('Installing hook on account with ID %s.', $account->getId()));

            //Get the HookFile code
            $auth = mt_rand();
            $hookfile = $this->get('heliopanel.hook_manager')->getCode($auth);

            //Get the HelioHost account
            $acct = $api->getAccount($account->getUsername());

            //Calculate the filename
            $filename = mt_rand().'.php';
            $ftpPath = 'public_html/'.$filename;
            $hookUrl = 'http://'.$acct->domain.'/'.$filename;

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

            $em->flush();

            return true;
        }
	}
}