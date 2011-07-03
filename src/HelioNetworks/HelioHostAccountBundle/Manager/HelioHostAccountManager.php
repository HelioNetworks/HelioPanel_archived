<?php

namespace HelioNetworks\HelioHostAccountBundle\Manager;

use HelioNetworks\HelioHostAccountBundle\Repository\AccountRepository;

class HelioHostAccountManager
{
    protected $connection;

    protected $server;
    protected $username;
    protected $password;
    protected $database;

    protected function initialize()
    {
        if(!$this->connection)
        {
            $this->connection = mysql_connect($this->server, $this->username, $this->password);
            mysql_select_db($this->database, $this->connection);
        }
    }

    public function __construct($server, $username, $password, $database)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function getAccountRepository()
    {
        $this->initialize();
        return new AccountRepository($this->connection);
    }

    public function __destruct()
    {
        if($this->connection)
        {
            mysql_close($this->connection);
            $this->connection = null;
        }
    }
}