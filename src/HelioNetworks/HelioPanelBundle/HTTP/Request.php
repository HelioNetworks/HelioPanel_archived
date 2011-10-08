<?php

namespace HelioNetworks\HelioPanelBundle\HTTP;

class Request
{
    protected $url;
    protected $data;
    protected $method;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function setData(array $data)
    {
        $this->data = http_build_query($data);
    }

    public function setRawData($data)
    {
        $this->data = $data;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getRawData()
    {
        return $this->data;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUrl()
    {
        return $this->url;
    }

   /**
    * Send the request.
    *
    * @deprecated Please use the wrapper instead
    */
    public function send()
    {
        $params = array('http' => array(
                  'method' => $this->method,
                  'content' => $this->data,
                  'timeout' => '5',
        ));

        $ctx = stream_context_create($params);

        return new Response(@file_get_contents($this->url, false, $ctx));
    }
}
