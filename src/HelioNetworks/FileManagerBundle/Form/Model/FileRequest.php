<?php

namespace HelioNetworks\FileManagerBundle\Form\Model;

use HelioNetworks\HelioPanelBundle\Entity\Hook;

class FileRequest
{
	protected $source;
	protected $dest;
	protected $data;

	public function setSource($source)
	{
		$this->source = $source;
	}

	public function getSource()
	{
		return $this->source;
	}

	public function setDest($dest)
	{
		$this->dest = $dest;
	}

	public function getDest()
	{
		return $this->dest;
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function setHook(Hook $hook)
	{
		$this->data = $hook->getContents($this->getSource());
	}
}