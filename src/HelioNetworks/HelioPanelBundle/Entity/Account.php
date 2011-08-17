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
}