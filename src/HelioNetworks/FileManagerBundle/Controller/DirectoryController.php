<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DirectoryController extends Controller {

    public function createAction() {}

    public function deleteAction() {}

    public function renameAction() {}

    public function enumerateAction()
    {
        $filesystem = $this->get('filesystem');

        $this->render('HelioNetworksFileManagerBundle:Direcotory:enumerate.html.twig', array(
            'keys' => $filesystem->keys(),
        ));
    }
}