<?php

/******************************************\
| cPanel Scripts Suite                     |
| (C) Copyright Bluestone Coding 2005-2009 |
|******************************************|
|           __    ___ ___  ___             |
|          /++\  | _ ) __|/ __|            |
|          \++/  | _ \__ \ (__             |
|           \/   |___/___/\___|            |
|                                          |
|******************************************|
| Suite Version 4.0                        |
| File last updated: 6/20/2009             |
| MySQL DATABASE CLASS                     |
\******************************************/

class database {
	
	public $platform = 'MySQLi';
	private $host;
	private $username;
	private $password;
	private $database;
	private $connection;
	public $lastqueryid;
	
	public function database() {
		global $db;
		//Transfer the info from the config file into the object and then kill the old array
		$this->host = $db['host'];
		$this->username = $db['user'];
		$this->password = $db['pass'];
		$this->database = $db['dtbs'];
		unset($GLOBALS['db']);
		//Make the connection
		$this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
		//Trash the password for security
		unset($this->password);
	}
	
	public function query($query) {
		global $page;
		$result = @$this->connection->query($query);
		if($this->connection->errno && is_object($page)) $page->error("sql", '', array($this->connection->error . "<br/>\n<i>{$query}</i>"));
		else if($this->connection->errno) die($this->connection->error);
		return $result;
	}
	
	public function getRowFromResult($result) {
		return $result->fetch_assoc();
	}
	
	public function escape($string="") {
		return $this->connection->escape_string($string);
	}

}

?>
