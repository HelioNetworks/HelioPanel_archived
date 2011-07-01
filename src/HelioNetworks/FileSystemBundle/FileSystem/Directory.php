<?php

namespace HelioNetworks\FileSystemBundle\FileSystem;

class Directory extends FileObject
{
    public function children()
    {
        $children = array();

        foreach($this->BaseFileSystem->scan($this->path) as $child)
        {
            if($child != '.' && $child != '..')
            {
                if($this->BaseFileSystem->isDir($child))
                {
                    $children[] = new Directory($child);
                }
                else
                {
                    $children[] = new File($child);
                }
            }
        }

        return $children;
    }
}