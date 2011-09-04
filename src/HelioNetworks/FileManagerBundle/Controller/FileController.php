<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use HelioNetworks\FileManagerBundle\Form\Model\FileRequest;
use HelioNetworks\FileManagerBundle\Form\Type\UploadFileRequestType;
use HelioNetworks\FileManagerBundle\Form\Model\UploadFileRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use HelioNetworks\FileManagerBundle\Form\Type\EditFileRequestType;
use HelioNetworks\FileManagerBundle\Form\Type\DeleteFileRequestType;
use HelioNetworks\FileManagerBundle\Form\Type\RenameFileRequestType;
use HelioNetworks\HelioPanelBundle\Controller\HelioPanelAbstractController;
use HelioNetworks\HelioPanelBundle\Entity\Account;
use HelioNetworks\FileManagerBundle\Form\Type\CreateFileRequestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FileController extends HelioPanelAbstractController
{
	protected $aceMappings = array(
		'php' => 'ace/mode/php',
		'js' => 'ace/mode/javascript',
	);

	/**
	 * @Route("/file/create", name="file_create")
	 * @Template()
	 */
	public function createAction()
	{
		$fileRequest = new FileRequest();
		$form = $this->createForm(new CreateFileRequestType(), $fileRequest);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$this->getHook()
					->touch($fileRequest->getSource());
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
		$fileRequest = new FileRequest();
		$form = $this->createForm(new RenameFileRequestType(), $fileRequest);

		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$this->getHook()
					->rename($fileRequest->getSource(), $fileRequest->getDest());

				return new Response();
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
		$fileRequest = new FileRequest();
		$form = $this->createForm(new DeleteFileRequestType(), $fileRequest);

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {
				$this->getHook()
					->rm($fileRequest->getSource());
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
		//TODO: Cleanup

		$request = $this->getRequest();
		$editFileRequest = new FileRequest();
		$editFileRequest->setSource($request->get('path'));
		$editFileRequest->setData($this->getHook()->get($editFileRequest->getSource()));
		$form = $this->createForm(new EditFileRequestType(), $editFileRequest);

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			if ($form->isValid()) {

				$this->getHook()
					->save($editFileRequest->getSource(), $editFileRequest->getData());
			}

			return new RedirectResponse($this->generateUrl('directory_list').'?path='.dirname($editFileRequest->getSource()));
		}

		$extension = substr(strrchr($editFileRequest->getSource(), '.'), 1);
		if(!$mode = @$this->aceMappings[$extension]) {
			$mode = 'ace/mode/text';
		}

		return array('form' => $form->createView(), 'mode' => $mode);
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
				$tempName = tempnam(sys_get_temp_dir(), 'uploaded_file_');
				$uploadFileRequest->getUploadedFile()
					->move(dirname($tempName), basename($tempName));
				$this->getHook()
					->save($editFileRequest->getFilename(), file_get_contents($tempName));
			}
		}
	}
}
