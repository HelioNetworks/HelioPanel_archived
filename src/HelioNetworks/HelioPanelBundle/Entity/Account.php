<?php

namespace HelioNetworks\HelioPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Account
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;

	/**
	 * The URL to the hookfile of this account.
	 *
	 * The hookfile allows HelioPanel to execute
	 * actions from the user's UNIX account.
	 *
	 * @ORM\Column(type="string")
	 */
	protected $hookfile;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $username;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $password;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="accounts")
	 */
	protected $user;
}