<?php

namespace HelioNetworks\HelioPanelBundle;

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

    /**
     * Rename a file.
     */
    public function rename($source, $dest)
    {
        $this->call(array(
            'action' => 'rename',
            'source' => $source,
            'dest' => $dest,
        ));
    }

    /**
     * Copy a file.
     */
    public function copy($source, $dest)
    {
        $this->call(array(
            'action' => 'copy',
            'source' => $source,
            'dest' => $dest,
        ));
    }

    /**
     * Create a file.
     */
    public function touch($source)
    {
        $this->call(array(
            'action' => 'touch',
            'source' => $source,
        ));
    }

    /**
     * Save a file.
     */
    public function save($source, $data)
    {
        $this->call(array(
            'action' => 'save',
            'source' => $source,
        ), array(
            'data' => $data,
        ));
    }

    /**
     * Get the contents of a file.
     */
    public function get($source)
    {
        return $this->call(array(
            'action' => 'get',
            'source' => $source,
        ));
    }

    /**
     * Create a directory.
     */
    public function mkdir($source)
    {
        $this->call(array(
            'action' => 'mkdir',
            'source' => $source,
        ));
    }

    /**
     * List the contents of a directory.
     */
    public function ls($source)
    {
        return unserialize($this->call(array(
            'action' => 'ls',
            'source' =>$source,
        )));
    }

    /**
     * Delete a file.
     *
     * @param string $source The file to delete
     */
    public function rm($source)
    {
        $this->call(array(
            'action' => 'rm',
            'source' => $source,
        ));
    }

    /**
     * Call hook.php.
     *
     * @param string $get_params Get parameters
     * @param string $post_params Post parameters
     */
    protected function call($get_params, $post_params = array())
    {
        $get_params['auth'] = $this->auth;
        $query = http_build_query($get_params);

        $url = $this->url . '?' . $query;

        if(empty($post_params)) {
            $contents = file_get_contents($url);
        } else {
            $contents = $this->doPostRequest($url, http_build_query($post_params));
        }

        if (($contents == '600 Not Implemented')) {
            global $username, $currentConfig;
            installHook($username, $currentConfig['password']);
        }

        return $contents;

    }

    /**
     * Make a POST request.
     *
     * @param string $url The url to call
     * @param string $data POST data
     * @param string $optional_headers Any optional headers to add
     */
    protected function doPostRequest($url, $data, $optional_headers = null)
    {
        $params = array('http' => array(
                  'method' => 'POST',
                  'content' => $data
        ));
        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            throw new Exception("Problem with $url, $php_errormsg");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problem reading data from $url, $php_errormsg");
        }
        return $response;
    }
}