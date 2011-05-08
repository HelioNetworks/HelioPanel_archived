<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DirectoryController extends Controller {

    public function createAction() {}

    public function deleteAction() {}

    public function renameAction() {}

    public function enumerateAction()
    {
        $filesystem = $this->get('helio_networks_file_manager.filesystem.ftp');

        $this->render('HelioNetworksFileManagerBundle:Direcotory:enumerate.html.twig', array(
            'keys' => $filesystem->keys(),
        ));
    }
}