<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use HelioNetworks\FileManagerBundle\Form\DeleteFileFormHandler;

use HelioNetworks\FileManagerBundle\Form\DeleteFileFormType;

use Gaufrette\Filesystem\Filesystem;

use Gaufrette\Filesystem\Adapter\Local;

use HelioNetworks\FileManagerBundle\Form\RenameFileFormHandler;

use HelioNetworks\FileManagerBundle\Form\RenameFileFormType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileController extends Controller {

    public $filesystem;

    public function __construct()
    {
        $adapter = new Local('/home1/area52/public_html/test_dir/');
        $filesystem = new Filesystem($adapter);

        $this->filesystem = $filesystem;
    }

    public function create() {}

    public function edit() {}

    public function rename()
    {

        $form = $this->get('form.factory')->create(new RenameFileFormType());

        $request = $this->get('request');

        if($request->getMethod() == 'POST') {

            $form->bindRequest($request);

            if($form->isValid()) {

                $handler = new RenameFileFormHandler($form, $request);

                $handler->process($this->filesystem);
            }
        }
    }

    public function delete()
    {
        $form = $this->get('form.factory')->create(new DeleteFileFormType());

        $request = $this->get('request');

        if($request->getMethod() == 'POST') {

            $form->bindRequest($request);

            if($form->isValid()) {

                $handler = new DeleteFileFormHandler($form, $request);

                $handler->process($this->filesystem);
            }
        }
    }
}