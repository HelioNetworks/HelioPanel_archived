<?php

namespace HelioNetworks\FileManagerBundle\Form\Model;

class RenameFileRequest
{
	protected $oldname;

	protected $newname;

	public function setOldname($oldname)
	{
		$this->oldname = $oldname;
	}

	public function getOldname()
	{
		return $this->oldname;
	}

	public function setNewname($newname)
	{
		$this->newname = $newname;
	}

	public function getNewname()
	{
		return $this->newname;
	}
}