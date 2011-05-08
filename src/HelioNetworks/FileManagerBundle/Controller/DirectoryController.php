<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DirectoryController extends Controller {

    function create() {}

    function delete() {}

    function rename() {}

    function enumerate()
    {
        $filesystem = $this->get('helionetworks_filemanager.filesystem.ftp');

        $this->render('HelioNetworksFileManagerBundle:Direcotory:enumerate.html.twig', array(
            'keys' => $filesystem->keys(),
        ));
    }
}