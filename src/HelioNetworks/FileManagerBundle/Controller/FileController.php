<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use HelioNetworks\FileManagerBundle\Form\RenameFileFormHandler;

use HelioNetworks\FileManagerBundle\Form\RenameFileFormType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileController extends Controller {

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

                $handler->process();
            }
        }
    }

    public function delete() {}
}