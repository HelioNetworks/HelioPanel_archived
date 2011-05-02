<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use HelioNetworks\FileManagerBundle\Form\RenameType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileController extends Controller {

    public function create() {}

    public function edit() {}

    public function rename() {

        $form = $this->get('form.factory')->create(new RenameType());


    }

    public function delete() {}
}