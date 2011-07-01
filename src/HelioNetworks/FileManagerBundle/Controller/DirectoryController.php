<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HelioNetworks\FileSystemBundle\FileSystem\FileSystem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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

    /**
     * @Template()
     * @Route("/filemanager", name="filemanager")
     */
    public function enumerateAction()
    {
        $filesystem = $this->getFileSystem();

        return array(
            'keys' => array(),
        );
    }
}