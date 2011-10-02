<?php

namespace HelioNetworks\HelioPanelBundle\HTTP;

use Symfony\Component\HttpKernel\Log\LoggerInterface;

class Wrapper
{
	protected $logger;

	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	/**
	 * Get a response from a request
	 *
	 * @return Response
	 */
	public function getResponse(Request $request)
	{
		$params = array('http' => array(
		                  'method' => $request->getMethod(),
		                  'content' => $request->getRawData(),
		                  'timeout' => '5',
		));

		$ctx = stream_context_create($params);
		$contents = @file_get_contents($request->getUrl(), false, $ctx);

      		$this->logger->debug(sprintf('Got response from %s with contents: %s', $request->getUrl(), $contents));
      
		return new Response($contents);
	}
}