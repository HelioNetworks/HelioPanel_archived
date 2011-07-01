<?php

namespace HelioNetworks\FileSystemBundle;

use Symfony\Component\DependencyInjection\ContainerAware;

class Directory extends ContainerAware
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function children()
    {
        $children = scandir($this->path);

        $contents = array();

        foreach($children as $child)
        {
            if($child != '.' && $child != '..')
            {
                if(is_dir($child))
                {
                    $contents[] = new Directory($entry);
                }
                else
                {
                    $contents[] = new File($path);
                }
            }
        }

        return $contents;
    }

    public function getName()
    {
        return basename($path);
    }
}