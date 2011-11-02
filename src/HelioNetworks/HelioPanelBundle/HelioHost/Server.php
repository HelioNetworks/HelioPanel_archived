<?php

namespace HelioNetworks\HelioPanelBundle\HelioHost;

use HelioNetworks\HelioPanelBundle\HTTP\Wrapper;
use HelioNetworks\HelioPanelBundle\HTTP\Request;

class Server
{
	protected $url;
	protected $auth;
	protected $wrapper;

	public function setWrapper(Wrapper $wrapper)
	{
		$this->wrapper = $wrapper;
	}

	public function __call($function, array $params)
	{
		$req = new \stdClass();
		$req->__auth = $this->key;
		$req->__function = $function;
		$req->__params = $params;

		$data = json_encode($req);

		$request = new Request($this->url);
		$request->setRawData($data);
		$request->setMethod('POST');

		$resp = $this->wrapper->getResponse($request);

		return json_decode($resp->getData());
	}
}