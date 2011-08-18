<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use HelioNetworks\HelioPanelBundle\Entity\Account;
use HelioNetworks\FileManagerBundle\Form\Type\CreateFileRequestType;
use HelioNetworks\FileManagerBundle\Form\Model\CreateFileRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FileController extends Controller
{
	/**
	 * @return Account
	 */
	public function getCurrentAccount()
	{
		//TODO: Support multiple accounts.
		$accounts = $this->get('security.context')->getToken()->getUser()->getAccounts();

		return $accounts[0];
	}

	/**
	 * @Route("/file/create", name="file_create")
	 * @Template()
	 */
	public function create()
	{
		$createFileRequest = new CreateFileRequest();
		$form = $this->createForm(new CreateFileRequestType(), $createFileRequest);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$hook = $this->getCurrentAccount()->getFileRepository();

				$hook->touch($createFileRequest->getFilename());
			}
		}
	}
}
