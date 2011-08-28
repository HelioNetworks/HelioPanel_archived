<?php

namespace HelioNetworks\HelioPanelBundle\Hook;

interface HookSectionInterface
{
	/**
	 * Returns the name of the function
	 * Must be unique
	 */
	public function getName();

	/**
	 * Returns the code of the function
	 * If the code has a return statement
	 * the result will be passed to the
	 * caller
	 */
	public function getCode();
}