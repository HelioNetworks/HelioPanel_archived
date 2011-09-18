<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use HelioNetworks\FileManagerBundle\Form\Type\CreateFileRequestType;
use HelioNetworks\FileManagerBundle\Form\Model\CreateFileRequest;
use HelioNetworks\HelioPanelBundle\Controller\HelioPanelAbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DirectoryController extends HelioPanelAbstractController
{
    /**
     * @Route("/directory/list/{path}", name="directory_list", requirements={"path" = ".+"}, defaults={"path" = "/"})
     * @Route("/directory/list/", defaults={"path" = "/"})
     * @Template()
     */
    public function listAction($path)
    {
        $path = preg_replace('{\/+}', '/', $path);

        $files = $this->getHook()->listDirectory($path);
        $editors = $this->get('heliopanel.editor_manager')->getEditors();
        $breadcrumbs = $this->getBreadcrumbs($path);

        return array('files' => $files, 'path' => $path, 'editors' => $editors, 'breadcrumbs' => $breadcrumbs);
    }

    /**
     * Get a list of breadcrumbs for a path
     *
     * @var string $path The path to get breadcrumbs for
     */
    protected function getBreadcrumbs($path)
    {
        $crumbs = array(
            array(
                'name' => 'home',
                'source' => '/',
            )
        );
        $bits = explode('/', $path);
        $currentPath = '';
        foreach ($bits as $bit) {
            $currentPath .= '/'.$bit;
            if ($bit) {
                $crumbs[] = array(
                    'name' => $bit,
                    'source' => $currentPath,
                );
            }
        }

        return $crumbs;
    }

    //Note: moveAction will not be created in favor of renameAction
    //TODO: renameAction

    /**
     * @Route("/directory/create", name="directory_create")
     * @Template()
     */
    public function createAction()
    {
        $createFileRequest = new CreateFileRequest();
        $form = $this->createForm(new CreateFileRequestType(), $createFileRequest);

        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->getHook()
                    ->mkdir($createFileRequest->getFilename());
            }
        }

        return array('form' => $form->createView());
    }

    //TODO: deleteAction
}
