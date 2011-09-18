<?php

namespace HelioNetworks\HelioPanelBundle\Hook;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use HelioNetworks\HelioPanelBundle\HTTP\Request;

class Hook
{
    protected $auth;
    protected $url;
    protected $logger;

    public function setLogger(LoggerInterface $logger)
    {
    	$this->logger = $logger;
    }

    protected function debug($message)
    {
    	if ($logger = $this->logger) {
    		$logger->debug($message);
    	}
    }

    /**
     * Call hook.php.
     *
     * @param string $post_params Post parameters
     */
    public function __call($name, array $arguments)
    {
        $arguments['__name'] = $name;
        $arguments['__auth'] = $this->auth;

        $request = new Request($this->url);
        $request->setData($arguments);
        $request->setMethod('POST');

        $this->debug(print_r($request, true));

        try	{
            $contents = @unserialize($request->send()->getData());
        } catch (\Exception $ex) {
            $contents = null;
        }

        $this->debug($contents);

        return $contents;
    }
}
