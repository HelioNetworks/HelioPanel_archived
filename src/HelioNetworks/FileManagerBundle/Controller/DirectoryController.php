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

        return $this->render('HelioNetworksFileManagerBundle:Directory:enumerate.html.twig', array(
            'keys' => $filesystem->keys(),
        ));
    }
}