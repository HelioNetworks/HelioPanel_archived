<?php

namespace HelioNetworks\HelioPanelBundle\HelioHost;

class API
{
	protected $url;
	protected $key;

	public function __construct($url, $key)
	{
		$this->url = $url;
		$this->key = $key;
	}
}