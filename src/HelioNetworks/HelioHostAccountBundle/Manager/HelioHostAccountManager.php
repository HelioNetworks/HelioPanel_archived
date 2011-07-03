<?php

namespace HelioNetworks\HelioHostAccountBundle\Manager;

use HelioNetworks\HelioHostAccountBundle\Repository\AccountRepository;

class HelioHostAccountManager
{
    protected $connection;

    public function __construct($server, $username, $password)
    {
        $this->connection = mysql_connect($server, $username, $password);
    }

    public function getAccountRepository()
    {
        return new AccountRepository($this->connection);
    }

    public function __destruct()
    {
        mysql_close($this->connection);
    }
}