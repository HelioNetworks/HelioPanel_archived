<?php
namespace HelioNetworks\FileManagerBundle\Entity;

class Rename {

    protected $source;

    protected $destination;

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function setDestination($destination)
    {
        $this->destination = $destination;
    }
}