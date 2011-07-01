<?php

namespace HelioNetworks\FileSystemBundle\FileSystem;

use HelioNetworks\FileSystemBundle\BaseFileSystem;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use HelioNetworks\FileSystemBundle\Exception\SecurityBreachException;

class FileSystem
{
    protected $BaseFileSystem;

    public function __construct($directory = '/', $username = 'root')
    {
        if($directory == '/' || $username = 'root')
        {
            throw new SecurityBreachException();
        }

        $this->BaseFileSystem = new BaseFileSystem($directory, $username);
    }

    public function get($key)
    {
        $path = $this->computePath($key);

        if(is_dir($path))
        {
            $directory = new Directory($path);
            $directory->setBaseFileSystem($this->BaseFileSystem);

            return $directory;
        }
        elseif(is_file($path))
        {
            $file = new File($path);
            $file->setBaseFileSystem($this->BaseFileSystem);

            return $file;
        }
        else
        {
            throw new FileNotFoundException($path);
        }
    }
}