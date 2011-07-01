<?php

namespace HelioNetworks\FileSystemBundle\FileSystem;


class FileSystemManager
{
    public function create($directory, $username)
    {
        return new FileSystem($directory, $username);
    }
}