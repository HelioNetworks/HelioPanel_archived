<?php

namespace HelioNetworks\FileManagerBundle\Form\Model;

class FileRequest
{
	protected $path;
	protected $newPath;
	protected $data;

	public function setPath($path)
	{
		$this->path = $path;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function setNewPath($newPath)
	{
		$this->newPath = $newPath;
	}

	public function getNewPath()
	{
		return $this->newPath;
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}
}