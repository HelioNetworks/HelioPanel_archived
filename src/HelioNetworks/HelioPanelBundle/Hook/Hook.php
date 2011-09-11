<?php

namespace HelioNetworks\HelioPanelBundle\Hook;

use HelioNetworks\HelioPanelBundle\HTTP\Request;

class Hook
{
    protected $auth;
    protected $url;

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
        $request->setMethod('POST');

        try	{
        	$contents = @unserialize($request->send()->getData());
        } catch (\Exception $ex) {
        	$contents = null;
        }

        return $contents;
    }
}