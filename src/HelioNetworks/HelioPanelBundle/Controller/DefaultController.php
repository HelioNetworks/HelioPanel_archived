<?php

namespace HelioNetworks\HelioPanelBundle\Controller;

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
	* Make a POST request.
	*
	* @param string $url The url to call
	* @param string $data POST data
	* @param string $optional_headers Any optional headers to add
	*/
	protected function doPostRequest($url, $data, $optional_headers = null)
	{
		if(is_array($data)) {
			$data = http_build_query($data);
		}

		$params = array('http' => array(
	                  'method' => 'POST',
	                  'content' => $data
		));
		if ($optional_headers !== null) {
			$params['http']['header'] = $optional_headers;
		}
		$ctx = stream_context_create($params);
		$fp = @fopen($url, 'rb', false, $ctx);
		if (!$fp) {
			throw new \Exception("Problem with $url, $php_errormsg");
		}
		$response = @stream_get_contents($fp);
		if ($response === false) {
			throw new \Exception("Problem reading data from $url, $php_errormsg");
		}
		return $response;
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

			$hook_url = $this->doPostRequest('http://heliopanel.heliohost.org/install/autoinstall.php', array(
				'username' => $username,
				'password' => $password,
				'hookfile' => $hookfile,
			));

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
