<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use HelioNetworks\FileManagerBundle\Form\CreateFileFormHandler;

use HelioNetworks\FileManagerBundle\Form\CreateFileFormType;

use HelioNetworks\FileManagerBundle\Form\DeleteFileFormHandler;

use HelioNetworks\FileManagerBundle\Form\DeleteFileFormType;

use HelioNetworks\FileManagerBundle\Form\RenameFileFormHandler;

use HelioNetworks\FileManagerBundle\Form\RenameFileFormType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileController extends Controller {

    public function create()
    {
        $form = $this->get('form.factory')->create(new CreateFileFormType());

        $handler = new CreateFileFormHandler($form, $this->get('request'));
        $handler->process($this->get('helionetworks_filemanager.filesystem.ftp'));

        return $this->render('HelioNetworksFileManagerBundle:File:create.html.twig',array(
		    'form' => $form->createView(),
		));
    }

    public function edit() {}

    public function rename()
    {
        $form = $this->get('form.factory')->create(new RenameFileFormType());

        $handler = new RenameFileFormHandler($form, $this->get('request'));
        $handler->process($this->get('helionetworks_filemanager.filesystem.ftp'));

        return $this->render('HelioNetworksFileManagerBundle:File:rename.html.twig',array(
		    'form' => $form->createView(),
		));
    }

    public function delete()
    {
        $form = $this->get('form.factory')->create(new DeleteFileFormType());

        $handler = new DeleteFileFormHandler($form, $this->get('request'));
        $handler->process($this->get('helionetworks_filemanager.filesystem.ftp'));

        return $this->render('HelioNetworksFileManagerBundle:File:delete.html.twig',array(
		    'form' => $form->createView(),
		));
    }
}