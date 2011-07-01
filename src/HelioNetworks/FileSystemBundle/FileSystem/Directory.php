<?php

namespace HelioNetworks\FileSystemBundle;

class Directory
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function contents()
    {
        $contents = scandir($this->path);


    }
}