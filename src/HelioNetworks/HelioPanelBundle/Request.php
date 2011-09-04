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

		$params = array('http' => array(
	                  'method' => 'POST',
	                  'content' => $data
		));


		//if ($optional_headers !== null) {
		//	$params['http']['header'] = $optional_headers;
		//}

		$ctx = stream_context_create($params);

		return @file_get_contents($this->url, false, $ctx);
	}
}