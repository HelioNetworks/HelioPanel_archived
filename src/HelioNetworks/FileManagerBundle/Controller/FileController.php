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
     * @Route("/file/edit/{path}", name="file_edit", requirements={"path" = ".+"})
     * @Template()
     */
    public function editAction($path)
    {
        //TODO: Cleanup

        $fileRequest = new FileRequest();
        $fileRequest->setSource($path);
        $fileRequest->setHook($this->getHook());

        $form = $this->createForm(new EditFileRequestType(), $fileRequest);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {

                $this->getHook()
                    ->save($fileRequest->getSource(), $fileRequest->getData());
            }

            return new RedirectResponse($this->generateUrl('directory_list', array('path' => dirname($fileRequest->getSource()))));
        }

        $editors = $this->get('heliopanel.editor_manager')->getEditors();
        $editor = $editors[(int)$request->query->get('editor')];

        return array('form' => $form->createView(), 'editor' => $editor);
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

        return array('form' => $form->createView());
    }
}
