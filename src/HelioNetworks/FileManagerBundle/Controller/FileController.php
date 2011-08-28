<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use HelioNetworks\FileManagerBundle\Form\Type\UploadFileRequestType;

use HelioNetworks\FileManagerBundle\Form\Model\UploadFileRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use HelioNetworks\FileManagerBundle\Form\Type\EditFileRequestType;
use HelioNetworks\FileManagerBundle\Form\Model\EditFileRequest;
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

				return new RedirectResponse('/directory/list?path=/');
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
		$request = $this->getRequest();
		$editFileRequest = new EditFileRequest();
		$editFileRequest->setFilename($request->get('file'));
		$editFileRequest->setData($this->getHook()->get($editFileRequest->getFilename()));
		$form = $this->createForm(new EditFileRequestType(), $editFileRequest);

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$this->getHook()
					->save($editFileRequest->getFilename(), $editFileRequest->getData());
			}

			return new RedirectResponse($this->generateUrl('file_edit'));
		}


		return array('form' => $form->createView());
	}

	/**
	 * @Route("/file/upload", name="file_upload")
	 * @Template()
	 */
	public function uploadAction()
	{
		$request = $this->getRequest();
		$uploadFileRequest = new UploadFileRequest();
		$form = $this->createForm(new UploadFileRequestType(), $uploadFileRequest);

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				//TODO: This is messy
				$id = uniqid();
				$uploadFileRequest->getUploadedFile()
					->move('/tmp', $id);
				$this->getHook()
					->save($editFileRequest->getFilename(), file_get_contents('/tmp/'.$id));
			}
		}
	}
}
