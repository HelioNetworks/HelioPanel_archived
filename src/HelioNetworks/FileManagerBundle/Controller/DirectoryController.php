<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HelioNetworks\FileSystemBundle\FileSystem\FileSystem;

class DirectoryController extends Controller {

    /**
     * @return FileSystem
     */
    public function getFileSystem()
    {
        $this->get('filesystem_manager')->create('/home/area52', 'area52');
    }

    public function createAction() {}

    public function deleteAction() {}

    public function renameAction() {}

    public function enumerateAction()
    {
        $filesystem = $this->get('filesystem_manager');

        return $this->render('HelioNetworksFileManagerBundle:Directory:enumerate.html.twig', array(
            'keys' => $filesystem->keys(),
        ));
    }
}