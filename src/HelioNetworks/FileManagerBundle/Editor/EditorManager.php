<?php

namespace HelioNetworks\FileManagerBundle\Editor;

class EditorManager
{
	protected $editors;

	public function addEditor(EditorInterface $editor)
	{
		$this->editors[] = $editor;
	}

    public function getEditors()
    {
        return $this->editors;
    }
}