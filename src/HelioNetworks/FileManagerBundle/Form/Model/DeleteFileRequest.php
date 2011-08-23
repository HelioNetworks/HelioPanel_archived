<?php

namespace HelioNetworks\FileManagerBundle\Form\Model;

class DeleteFileRequest
{
	protected $filename;

	public function setFilename($filename)
	{
		$this->filename = $filename;
	}

	public function getFilename()
	{
		return $this->filename;
	}
}