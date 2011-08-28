<?php

namespace HelioNetworks\HelioPanelBundle\Hook;

use HelioNetworks\HelioPanelBundle\Request;

class Hook
{
    protected $auth;

    /**
     * Path to hook.php (No question mark!)
     */
    protected $url;

    public function __construct($url, $auth)
    {
        $this->url = $url;
        $this->auth = $auth;
    }

    /**
     * Call hook.php.
     *
     * @param string $post_params Post parameters
     */
    public function __call($name, array $arguments)
    {
        $arguments['__name'] = $name;
        $arguments['__auth'] = $this->auth;

        $request = new Request($this->url);
        $request->setData($arguments);
        $response = $request->send();

        return @unserialize($response);
    }
}