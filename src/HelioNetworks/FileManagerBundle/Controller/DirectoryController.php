<?php

namespace HelioNetworks\FileManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DirectoryController extends Controller
{
	protected function getHook()
	{
		return $this->get('security.context')
			->getToken()
			->getUser()
			->getActiveAccount()
			->getFileRepository();
	}

	/**
	 * @Route("/directory/list", name="directory_list")
	 * @Template()
	 */
	public function listAction()
	{
		$path = $this->getRequest()->get('path');
		$files = $this->getHook()->ls($path);

		return array('files' => $files);
	}
}
