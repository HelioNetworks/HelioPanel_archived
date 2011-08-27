<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use HelioNetworks\FileManagerBundle\Form\Type\DeleteFileRequestType;
use HelioNetworks\FileManagerBundle\Form\Model\DeleteFileRequest;
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
		$request = $this->getRequest();
		$createFileRequest = new CreateFileRequest();
		$form = $this->createForm(new CreateFileRequestType(), $createFileRequest);

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$this->getHook()
					->touch($request->get('path').$createFileRequest->getFilename());
			}
		}

		return array('form' => $form->createView());
	}

	//Note: moveAction will not be created in favor of renameAction

	/**
	 * @Route("/file/rename", name="file_rename")
	 * @Template()
	 */
	public function renameAction()
	{
		$request = $this->getRequest();
		$renameFileRequest = new RenameFileRequest();
		$renameFileRequest->setOldname($request->get('path'));
		$form = $this->createForm(new RenameFileRequestType(), $renameFileRequest);

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$this->getHook()
					->rename($renameFileRequest->getOldname(), $renameFileRequest->getNewName());
			}
		}

		return array('form' => $form->createView());
	}

	/**
	* @Route("/file/delete", name="file_delete")
	* @Template()
	*/
	public function deleteAction()
	{
		$request = $this->getRequest();
		$deleteFileRequest = new DeleteFileRequest();
		$form = $this->createForm(new DeleteFileRequestType(), $deleteFileRequest);

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$this->getHook()
					->rm($deleteFileRequest->getFilename());
			}
		}

		return array('form' => $form->createView());
	}

	/**
	 * @Route("/file/edit", name="file_edit")
	 * @Template()
	 */
	public function editAction()
	{
		$file = $this->getRequest()->get('file');
		$hook = $this->getHook();

		$data = $hook->get($file);

		$_SESSION['data'] = $data;

		return array('file' => $file);
	}
}
