<?php

namespace HelioNetworks\HelioPanelBundle\Entity;

use HelioNetworks\HelioPanelBundle\Hook\Hook;
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

	//TODO: Make this a separate entity
	/**
	 * The URL to the hookfile of this account.
	 *
	 * The hookfile allows HelioPanel to execute
	 * actions from the user's UNIX account.
	 *
	 * @ORM\Column(type="string", unique=true)
	 */
	protected $hookfile;

	/**
	 * Auth to the hookfile.
	 *
	 * @ORM\Column(type="string")
	 */
	protected $hookfileauth;

	/**
	 * @ORM\Column(type="string", unique=true)
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

	/**
	 * Get the hook for this account.
	 *
	 *  @return Hook
	 */
	public function getHook()
	{
		return new Hook($this->hookfile, $this->hookfileauth);
	}

	public function __toString()
	{
		return $this->getUsername();
	}

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set hookfile
     *
     * @param string $hookfile
     */
    public function setHookfile($hookfile)
    {
        $this->hookfile = $hookfile;
    }

    /**
     * Get hookfile
     *
     * @return string
     */
    public function getHookfile()
    {
        return $this->hookfile;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set user
     *
     * @param HelioNetworks\HelioPanelBundle\Entity\User $user
     */
    public function setUser(\HelioNetworks\HelioPanelBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return HelioNetworks\HelioPanelBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set hookfileauth
     *
     * @param string $hookfileauth
     */
    public function setHookfileauth($hookfileauth)
    {
        $this->hookfileauth = $hookfileauth;
    }

    /**
     * Get hookfileauth
     *
     * @return string
     */
    public function getHookfileauth()
    {
        return $this->hookfileauth;
    }
}