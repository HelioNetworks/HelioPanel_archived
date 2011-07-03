<?php

namespace HelioNetworks\HelioHostAccountBundle\Manager;

use HelioNetworks\HelioHostAccountBundle\Repository\AccountRepository;

class HelioHostAccountManager
{
    protected $connection;

    public function __construct($server, $username, $password, $database)
    {
        $this->connection = mysql_connect($server, $username, $password);
        mysql_select_db($database, $this->connection);
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