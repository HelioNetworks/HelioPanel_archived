<?php

/******************************************\
| cPanel Scripts Suite					   |
| (C) Copyright Bluestone Coding 2005-2007 |
|******************************************|
|         __    ___ ___  ___ 			   |
|        /++\  | _ ) __|/ __|			   |
|        \++/  | _ \__ \ (__ 			   |
|         \/   |___/___/\___|			   |
|										   |
|******************************************|
| Suite Version 3.0						   |
| File last updated: 9/7/07				   |
| CONFIGURATION CLASS					   |
\******************************************/

class info {
    
	public $settings = array();
	public $language = array();
	
	//--------------------------------------------
	// Post-instantiation auto-run
	//--------------------------------------------	
	public function __construct() {
		$this->getConfig();
		$this->getLang();
	}
	
	//--------------------------------------------
	// Retrieve configuration info from database
	//--------------------------------------------	
	private function getConfig() {
		global $database;
		$this->settings = $database->getConfig();
	}
	
	//--------------------------------------------
	// Retrieve language info from database
	//--------------------------------------------	
	private function getLang() {
		include DIR."/lang/global.php";
		if (file_exists(DIR."/lang/".PAGE.".php")){
			include DIR."/lang/".PAGE.".php";
			$this->language = array_merge($lang_global,$lang_extra);
		} else
			$this->language = $lang_global;
	}

}

?>
