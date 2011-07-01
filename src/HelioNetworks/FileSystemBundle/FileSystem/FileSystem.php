<?php

namespace HelioNetworks\FileSystemBundle\FileSystem;

use HelioNetworks\FileSystemBundle\FileSystem\BaseFileSystem;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use HelioNetworks\FileSystemBundle\Exception\SecurityBreachException;

class FileSystem
{
    protected $BaseFileSystem;

    public function __construct($directory = '/', $username = 'root')
    {
        if(($directory == '/') || ($username == 'root'))
        {
            throw new SecurityBreachException();
        }

        $this->BaseFileSystem = new BaseFileSystem($directory, $username);
    }

    public function get($key)
    {
        if($this->BaseFileSystem->isDir($key))
        {
            $directory = new Directory($key);
            $directory->setBaseFileSystem($this->BaseFileSystem);

            return $directory;
        }
        elseif($this->BaseFileSystem->isFile($key))
        {
            $file = new File($key);
            $file->setBaseFileSystem($this->BaseFileSystem);

            return $file;
        }
        else
        {
            throw new FileNotFoundException($path);
        }
    }
}