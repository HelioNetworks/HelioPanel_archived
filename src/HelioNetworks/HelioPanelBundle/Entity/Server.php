<?php

namespace HelioNetworks\HelioPanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HelioNetworks\HelioPanelBundle\HelioHost\Server as BaseServer;

/**
 * HelioNetworks\HelioPanelBundle\Entity\Server
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Server extends BaseServer
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    protected $url;

    /**
     * @var string $auth
     *
     * @ORM\Column(name="auth", type="string", length=255)
     */
    protected $auth;


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
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set auth
     *
     * @param string $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get auth
     *
     * @return string
     */
    public function getAuth()
    {
        return $this->auth;
    }
}