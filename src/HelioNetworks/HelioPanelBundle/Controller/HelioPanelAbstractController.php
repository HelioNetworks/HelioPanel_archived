<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use HelioNetworks\HelioPanelBundle\FileRepository;
use HelioNetworks\HelioPanelBundle\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class HelioPanelAbstractController extends Controller
{
	protected function getUser()
	{
		return $this->get('security.context')
			->getToken()
			->getUser();
	}

	/**
	 * @return FileRepository
	 */
	protected function getHook()
	{
		return $this->getActiveAccount()
			->getFileRepository();
	}

	/**
	 * @return Account
	 */
	protected function getActiveAccount()
	{
		if ($id = $this->get('session')->get('active_account_id')) {

			return $this->getDoctrine()
				->getRepository('HelioNetworksHelioPanelBundle:Account')
				->findOneById($id);
		}
	}

	protected function setActiveAccount(Account $account)
	{
		if ($account->getUser() !== $this->getUser()) {

			throw new \UnexpectedValueException('Account is not owned by current user.');
		}

		$this->get('session')
			->set('active_account_id', $account->getId());
	}
}