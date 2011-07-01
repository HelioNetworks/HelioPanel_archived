<?php

namespace HelioNetworks\FileSystemBundle\FileSystem;

use Symfony\Component\DependencyInjection\ContainerAware;

class File extends ContainerAware
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getContents()
    {
        return file_get_contents($this->path);
    }

    public function setContents($contents)
    {
        file_put_contents($this->path, $contents);
    }
}