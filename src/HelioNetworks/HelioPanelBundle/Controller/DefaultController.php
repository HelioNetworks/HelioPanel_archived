<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

use HelioNetworks\HelioPanelBundle\Request;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	/**
	 * Loads the init file.
	 */
	public function loadInit()
	{
		$_SESSION['username'] = 'dummy';
		try {
			require_once __DIR__.'/../../../../web/init.php';
		} catch (\Exception $ex) {
			//Do nothing.
		}
		unset($_SESSION['username']);
	}

    /**
     * @Route("/", name="heliopanel_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Installs hook.php on the user's site.
     *
     * @Route("/configure", name="heliopanel_configure")
     * @Template()
     */
    public function configureAction()
    {
    	if($this->getRequest()->isXmlHttpRequest()) {
			$this->loadInit();

			$auth = base_convert(mt_rand(0x1D39D3E06400000, 0x41C21CB8E0FFFFFF), 10, 36);

			$username = $this->getRequest()->get('username');
			$password = $this->getRequest()->get('password');
			$hookfile = file_get_contents(__DIR__.'/../../../../web/hook.php');
			$hookfile = str_replace('%authKey%', $auth, $hookfile);

			$postRequest = new Request('http://heliopanel.heliohost.org/install/autoinstall.php');
			$postRequest->setData(array(
				'username' => $username,
				'password' => $password,
				'hookfile' => $hookfile,
			));
			$hook_url = $postRequest->send();

			if(empty($hook_url) || strpos($hook_url, '500 Internal Server Error')) {
				throw new \RuntimeException('Hook url is empty or is invalid.');
			} else {
				global $config, $configManager;

				$config[$username] = array(
					'password' => $password,
					'hook_php' => $hook_url,
					'hook_auth'=> $auth,
				);

				$configManager = new \ConfigManager();
				$configManager->setConfig($config);

				return new Response();
			}

    	}

    	return array();
    }
}
