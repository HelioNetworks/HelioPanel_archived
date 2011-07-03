<?php

namespace HelioNetworks\HelioHostAccountBundle\Repository;

class Repository
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    protected function query($query)
	{
	    mysql_ping($this->connection);
	    $result = mysql_query($query, $this->connection);
	    return mysql_fetch_array($result);
	}
}