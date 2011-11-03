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
     * @ORM\OneToOne(targetEntity="Hook")
     */
    protected $hook;

    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="accounts")
     */
    protected $server;

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
     * Set hook
     *
     * @param HelioNetworks\HelioPanelBundle\Entity\Hook $hook
     */
    public function setHook(\HelioNetworks\HelioPanelBundle\Entity\Hook $hook)
    {
        $this->hook = $hook;
    }

    /**
     * Get hook
     *
     * @return HelioNetworks\HelioPanelBundle\Entity\Hook
     */
    public function getHook()
    {
        return $this->hook;
    }

    public function setServer(\HelioNetworks\HelioPanelBundle\Entity\Server $server)
    {
    	$this->server = $server;
    }

    public function getServer()
    {
    	return $this->server;
    }
}
