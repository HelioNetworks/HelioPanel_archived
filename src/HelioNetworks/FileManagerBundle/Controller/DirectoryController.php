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
		$files = $this->getHook()->ls($this->getRequest()->get('path'));

		return array('files' => $files);
	}

	//Note: moveAction will not be created in favor of renameAction
	//TODO: renameAction

	//TODO: createAction

	//TODO: deleteAction
}
