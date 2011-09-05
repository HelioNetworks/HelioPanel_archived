<?php

namespace HelioNetworks\HelioPanelBundle\Entity;

use HelioNetworks\HelioPanelBundle\Hook\Hook as BaseHook;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Hook extends BaseHook
{
	/**
	* @ORM\Id
	* @ORM\Column(type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	protected $id;

	/**
	* The URL to the hook of this account.
	*
	* The hookfile allows HelioPanel to execute
	* actions from the user's UNIX account.
	*
	* @ORM\Column(type="string", unique=true)
	*/
	protected $url;

	/**
	 * Auth to the hook.
	 *
	 * @ORM\Column(type="string")
	 */
	protected $auth;

	public function getAuth()
	{
		return $this->auth;
	}

	public function setAuth($auth)
	{
		$this->auth = $auth;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function setUrl($url)
	{
		$this->url = $url;
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
}