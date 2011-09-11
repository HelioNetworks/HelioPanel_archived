<?php

namespace HelioNetworks\FileManagerBundle\Editor;

interface EditorInterface
{
	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return array
	 */
	public function getAssets();
}