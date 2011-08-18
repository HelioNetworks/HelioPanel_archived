<?php

namespace HelioNetworks\HelioPanelBundle;

class Request
{
	protected $url;
	protected $data;

	public function __construct($url)
	{
		$this->url = $url;
	}

	public function setData(array $data)
	{
		$this->data = $data;
	}

   /**
	* Make a POST request.
	*/
	public function send()
	{
		$data = http_build_query($this->data);
		$url = $this->url;

		$params = array('http' => array(
	                  'method' => 'POST',
	                  'content' => $data
		));


		//if ($optional_headers !== null) {
		//	$params['http']['header'] = $optional_headers;
		//}

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
}