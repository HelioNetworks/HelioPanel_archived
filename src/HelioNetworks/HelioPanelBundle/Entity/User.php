<?php

namespace HelioNetworks\HelioPanelBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Account", mappedBy="user")
     */
    protected $accounts;

    /**
     * @ORM\OneToOne(targetEntity="Account")
     */
    protected $activeAccount;

    public function __construct()
    {
    	parent::__construct();

        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add accounts
     *
     * @param HelioNetworks\HelioPanelBundle\Entity\Account $accounts
     */
    public function addAccounts(\HelioNetworks\HelioPanelBundle\Entity\Account $accounts)
    {
        $this->accounts[] = $accounts;
    }

    /**
     * Get accounts
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Set activeAccount
     *
     * @param HelioNetworks\HelioPanelBundle\Entity\Account $activeAccount
     */
    public function setActiveAccount(\HelioNetworks\HelioPanelBundle\Entity\Account $activeAccount)
    {
    	if ($this->getAccounts()->contains($activeAccount)) {
    		$this->activeAccount = $activeAccount;
    	}
    }

    /**
     * Get activeAccount
     *
     * @return HelioNetworks\HelioPanelBundle\Entity\Account
     */
    public function getActiveAccount()
    {
        return $this->activeAccount;
    }
}