<?php

namespace HelioNetworks\HelioPanelBundle\Job;

use HelioNetworks\HelioPanelBundle\Entity\Account;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class SyncAccountJob extends ContainerAware implements ContainerAwareInterface
{
	protected $account;

	public function __construct(Account $account)
	{
		$this->account = $account;
	}

	public function process()
	{
		//Sync the account with the database
	}
}