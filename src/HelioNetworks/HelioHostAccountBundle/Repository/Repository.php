<?php

namespace HelioNetworks\HelioHostAccountBundle\Repository;

class Repository
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }
}