<?php

class FileRepository
{
    protected $auth;

    /**
     * Path to hook.php (No question mark!)
     */
    protected $url;

    public function getUrl()
    {
        return $this->url;
    }

    public function __construct($url, $auth)
    {
        $this->url = $url;
        $this->auth = $auth;
    }

    public function rename($source, $dest)
    {
        $params = array(
            'source' => $source,
            'dest' => $dest,
        );

        $this->call($params);
    }

    protected function call($get_params, $post_params = array())
    {
        $get_params['auth'] = $this->auth;
        $query = http_build_query($get_params);

        $url = $this->url . '?' . $query;

        //TODO: Call POST if $post_params not empty.

        return file_get_contents($url);
    }
}