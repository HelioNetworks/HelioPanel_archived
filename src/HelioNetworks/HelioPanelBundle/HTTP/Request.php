<?php

namespace HelioNetworks\HelioPanelBundle\HTTP;

class Request
{
	protected $url;
	protected $data;
	protected $method;

	public function __construct($url)
	{
		$this->url = $url;
	}

	public function setData(array $data)
	{
		$this->data = $data;
	}

	public function setMethod($method)
	{
		$this->method = $method;
	}

   /**
	* Send the request.
	*/
	public function send()
	{
		$params = array('http' => array(
                  'method' => $this->method,
                  'content' => http_build_query($this->data),
		));

		$ctx = stream_context_create($params);

		return new Response(@file_get_contents($this->url, false, $ctx));
	}
}