<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use HelioNetworks\FileManagerBundle\Form\Type\RenameFileRequestType;

use HelioNetworks\FileManagerBundle\Form\Model\RenameFileRequest;
use HelioNetworks\HelioPanelBundle\Controller\HelioPanelAbstractController;
use HelioNetworks\HelioPanelBundle\Entity\Account;
use HelioNetworks\FileManagerBundle\Form\Type\CreateFileRequestType;
use HelioNetworks\FileManagerBundle\Form\Model\CreateFileRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FileController extends HelioPanelAbstractController
{
	/**
	 * @Route("/file/create", name="file_create")
	 * @Template()
	 */
	public function createAction()
	{
		$createFileRequest = new CreateFileRequest();
		$form = $this->createForm(new CreateFileRequestType(), $createFileRequest);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$this->getHook()
					->touch($createFileRequest->getFilename());
			}
		}

		return array();
	}

	//Note: moveAction will not be created in favor of renameAction

	/**
	 * @Route("/file/rename", name="file_rename")
	 * @Template()
	 */
	public function renameAction()
	{
		$renameFileRequest = RenameFileRequest();
		$form = $this->createForm(new RenameFileRequestType(), $renameFileRequest);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$this->getHook()
					->rename($renameFileRequest->getOldname(), $renameFileRequest->getNewName());
			}
		}

		return array();
	}

	//TODO: deleteAction

	//TODO: saveAction
}
