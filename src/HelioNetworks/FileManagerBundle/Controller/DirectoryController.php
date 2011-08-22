<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use HelioNetworks\HelioPanelBundle\Controller\HelioPanelAbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DirectoryController extends HelioPanelAbstractController
{
	/**
	 * @Route("/directory/list", name="directory_list")
	 * @Template()
	 */
	public function listAction()
	{
		$path = $this->getRequest()->get('path');
		$files = $this->getHook()->ls($path);

		return array('files' => $files, 'path' => $path);
	}

	//TODO: renameAction

	//TODO: createAction

	//TODO: deleteAction
}
