<?php

namespace HelioNetworks\FileSystemBundle\FileSystem;

abstract class FileObject
{
    protected $BaseFileSystem;
    protected $path;

    public function setBaseFileSystem(BaseFileSystem $BaseFileSystem)
    {
        $this->BaseFileSystem = $BaseFileSystem;
    }

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function parentDir()
    {
        $directory = new Directory(dirname($this->path));
        $directory->setBaseFileSystem($this->BaseFileSystem);
    }

    public function getName()
    {
        return basename($path);
    }
}