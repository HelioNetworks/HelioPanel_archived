<?php

namespace HelioNetworks\FileSystemBundle\FileSystem;

use HelioNetworks\FileSystemBundle\Exception\SecurityBreachException;

class FileSystem
{
    protected $directory;
    protected $username;

    public function __construct($directory = '/', $username = 'root')
    {
        if($directory == '/' || $username = 'root')
        {
            throw new SecurityBreachException();
        }

        $this->directory = $directory;
        $this->username = $username;
    }
}