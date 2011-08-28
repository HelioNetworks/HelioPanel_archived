<?php

namespace HelioNetworks\FileManagerBundle\Form\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileRequest
{
	protected $path;
	protected $uploadedFile;

	public function getPath()
	{
		return $this->path;
	}

	public function setPath($path)
	{
		$this->path = $path;
	}

	public function getUploadedFile()
	{
		return $this->uploadedFile;
	}

	public function setUploadedFile(UploadedFile $uploadedFile)
	{
		$this->uploadedFile = $uploadedFile;
	}
}