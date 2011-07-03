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
| ACCOUNT MASTER CLASS                     |
\******************************************/

require_once DIR."/classes/plan.php";
require_once DIR."/xmlapi.php.inc";

class account {
    
    public $id;            // Account's ID
    public $username;      // Account's Username
    public $domain;        // Account's Domain
    private $password;     // User's Password
    public $passhash;      // User's Password Hash
    public $email;         // User's Email Address
    private $auth_key;     // Authorization Key
    public $plan;          // Plan Object
    public $reg_date;      // Account registration date
    public $exp_date;      // Account expiration date
    public $state;         // Account state
    public $ip;            // Account creator's IP address
    public $last_update;   // Last login
    public $reason;        // Reason for suspension
    public $hnname;        // HelioNet name
    public $admin_bypass;  // Auto-registration?
    private $xmlapi;       // XML API
    
    public static function getAccounts($where='') {
        global $database;
        $arrays = $database->getAccounts($where);
        $accounts = array();
        foreach ($arrays as $array) {
            $account = new account;
            // Give it some values
            $account->accountFromRow($array);
            $account->getPlan($array['plan']);
            $accounts[] = $account;
        }
        return $accounts;
    }
    
    public function __construct($where='') {
        global $database;
        if ($where == '') return;
        $array = $database->getAccount($where);
        $this->accountFromRow($array);
        $this->getPlan($array['plan']);
    }
    
    public function accountFromRow($array) {
        if (!is_array($array)) return;
        $this->id           = $array['id'];
        $this->username     = $array['username'];
        $this->domain       = $array['domain'];
        $this->passhash     = $array['password'];
        $this->email        = $array['email'];
        $this->auth_key     = $array['auth_key'];
        $this->reg_date     = $array['reg_date'];
        $this->exp_date     = $array['exp_date'];
        $this->state        = $array['state'];
        $this->ip           = $array['ip'];
        $this->last_update  = $array['last_update'];
        $this->reason       = $array['reason'];
        $this->hnname       = $array['hnname'];
        $this->admin_bypass = $array["admin_bypass"];
        if (isset($array['pid'])) {
            $array['id'] = $array['pid'];
            $array['plan'] = $array['plan_name'];
            $this->plan = new plan($array);
        }
    }
    
    private function initializeAPI() {
        $this->xmlapi = new xmlapi($this->plan->server->host);
        $this->xmlapi->hash_auth($this->plan->server->user, $this->plan->server->hash);
    }
    
    // Set the auth_key
    public function setAuth($auth_key) {
        $this->auth_key = $auth_key;
    }
    
    public function hasAuth() {
        if ($this->auth_key) return true;
        else return false;
    }
    
    public function getAuth() {
        return $this->auth_key;
    }
        
    // Set the password
    public function setPass($pass) {
        $this->password = $pass;
        $this->passhash = md5(sha1($pass . "hel10host"));
    }

    public function getPass() {
        return $this->password;
    }
    
    public function checkPass($pass) {
    	
    	$port = 2082;
    	$protocol = 'http';
    	$path = 'frontend/x3/index.phpcp';
    	$user = $this->username;
    	$server = $this->plan->server->host;
    	    	
    	$url = $protocol.'://'.$server.':'.$port.'/'.$path;
    	
	    set_time_limit(60); 
			
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		$cook_file = tempnam(sys_get_temp_dir(), "curl_login_cookie_");
		
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_HEADER, 1); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, 1); 
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);  
		curl_setopt($ch, CURLOPT_USERPWD, $user.":".$pass); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, "$cook_file"); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "$cook_file"); 
		
		# just discard the output. 
		$src = curl_exec($ch); 
		
		# grab http code 
		$extract = curl_getinfo($ch); 
		$httpcode = $extract['http_code']; 
		curl_close($ch); 
		
		# delete cookie file 
		if (file_exists($cook_file)) { 
			unlink($cook_file); 
		} 
		
		return ($httpcode >= 200 && $httpcode < 303);
    }
    
    public function getPlan($pid=0) {
        global $info, $page, $database;
        // Plan ID passed? 
        if ($pid) {
            $plan = $database->getPlan("p.id = '".$pid."'");
        // Plan stored with auth key?
        } else if ($this->auth_key) {
            $plan = $database->getPlanAndAuth("a.auth_key = '".$this->auth_key."'");
            if (!$plan) $page->error("auth");
            else if ($plan['state'] != 'OPEN') $page->error("akduplct");
        } else {
            // Plan in database?
            $array = $database->getPlanForAccount($this->id);
            if (isset($plan['pid'])) {
                $plan = new plan($array);
            // Plan in URL?
            } else if ($_GET['plan']) {
                $plan = $database->getPlan("p.id = " . intval($_GET['plan']) . " OR p.plan = '" . $database->escape($_GET['plan']) . "'");
                if (!$plan['pid']) $page->error("noplan");
            } else {
                // If all else fails, go to default.
                if (!$plan['pid'] AND $info->settings['default_plan']) {
                    $plan = $database->getPlan("p.id = '".$info->settings['default_plan']."'");
                    if (!$plan['pid']) $page->error("noplan");
                // And if even that fails, give out an error
                } else if (!$plan['pid']) {
                    $page->error("noplanpassed");
                }
            }
        }
        $this->plan = new plan($plan);
    }
    
    public static function getAcctsFromCP() {
        global $info;
        $servers = Array();
        $accounts = Array();
        foreach (plan::getPlans() as $plan) {
            $alreadyExists = false;
            foreach ($servers as $server) {
                if ($server->compare($plan->server)) {
                    $alreadyExists = true;
                    break;
                }
            }
            if (!$alreadyExists) {
                $object = $plan->server->getAcctsFromCP();
                foreach ($object as $account) $accounts[] = $account; 
            }
        }
        return $accounts;
    }
    
    public function addToCP() {
        global $info;
        $this->initializeAPI();
        $create = $this->xmlapi->createacct( array( domain         => $this->domain, 
                                                    username       => $this->username,
                                                    password       => $this->password,
                                                    plan           => $this->plan->package,
                                                    contactemail   => $this->email ) );
        return $create->result->statusmsg;
    }
    
    public function addToDB() {
        global $database;
        //Is validation needed?
        if ($this->state != "ACTIVE") {
            $this->state = $this->plan->validation ? 'VALIDATION' : 'ACTIVE';
            //More importantly, do we queue it?
            $this->state = $this->plan->disperse ? 'QUEUED' : $this->state;
        }
        //Does this account expire?
        list($number, $interval) = explode('|', $this->plan->expiration);
        $this->exp_date = ($number == 0) ? 'NULL' : "ADDDATE(NOW(), INTERVAL $number $interval)";
        //Add to the database!
        $database->addAcct($this);
    }
    
    public function removeFromDB() {
        global $database;
        $database->removeAccount($this->id);
    }
    
    public function rawSuspend() {
        global $info;
        $this->initializeAPI();
        $this->xmlapi->suspendacct(strtolower($this->username));
    }
    
    public function rawUnsuspend() {
        global $info;
        $this->initializeAPI();
        $this->xmlapi->unsuspendacct(strtolower($this->username));
    }
    
    public function suspend($message="SUSPENDED", $reason="") {
        global $database;
        if (isset($reason)) $database->setStateAndReason($this->id, $message, $database->escape($reason));
        else $database->setState($this->id, $message);
        $this->rawSuspend();
    }
    
    public function unsuspend() {
        global $database;
        $database->setState($this->id, "ACTIVE");
        $this->rawUnsuspend();
    }
    
    public function delete() {
        global $database;
        $this->initializeAPI();
        $this->xmlapi->removeacct(strtolower($this->username));
        if (!$this->exists()) $database->removeAccount($this->id);
        else return false;
        return true;
    }
    
    public function rawModify($array) {
        $this->initializeAPI();
        $array['user'] = $this->username;
        $this->xmlapi->modifyacct($array);
    }
    
    public function rawPasswordChange($password) {
        $this->initializeAPI();
        $this->xmlapi->passwd($this->username, $password); 
    }
    
    // Note: the properties will still need to be updated afterwards
    public function modify($array) {
        global $database;
        $dbvalues = array();
        $cpvalues = array();
        foreach ($array as $key => $value) {
            if (($key == "plan" && $this->plan->id != $value) || $value != $this->$key) {
                if ($key == "password") {
                    if (isset($value) AND $value) {
                        if ($array['state'] != "QUEUED") $this->rawPasswordChange($value);
                        $this->setPass($value);
                        $value = $this->passhash;
                    } else continue;
                } else if ($key == "plan") {
                    if ($this->plan->id == $value) continue;
                    $newplan = plan::getPlan("p.id = {$value}");
                    $dbvalues["plan"] = $newplan->id;
                    $cpvalues["PLAN"] = $newplan->package;
                    continue;
                }
                if (($key == "reg_date" OR $key == "exp_date" OR $key == "last_update") AND (!isset($value) OR !$value) OR $value == "NONE" OR $value == "NULL") $value = "NULL"; 
                $dbvalues[$key] = $value;
                if ($key == "username") $cpvalues["newuser"] = $value;
                if ($key == "domain") $cpvalues["domain"] = $value;
            }
        }
        if (empty($dbvalues)) return;
        $database->updateAccount($this->id, $dbvalues);
        if (!empty($cpvalues) AND $array['state'] != "QUEUED") $this->rawModify($cpvalues);
    }
    
    public function createFromQueue() {
        global $info, $database;
        // Temporarily suspend for validation?
        if ($this->plan->validation) {
            $this->rawSuspend();
            $database->setStateAndHash($this->id, $this->passhash, "VALIDATION");
        } else {
            $database->setStateAndHash($this->id, $this->passhash, "ACTIVE");
        }
        // Create the account - yay!
        $create = $this->addToCP();
        return $create;
    }
    
    public function cPanelVisit() {
        global $database;
        $database->cPanelVisit($this->username, $this->email);
    }
    
    public function setInactive() {
        global $database;
        $database->setInactive($this->plan, $this->username, $this->email);
    }
    
    public function exists() {
        if ($this->username == "") return false;
        return file_exists("/var/cpanel/users/{$this->username}");
    }
    
    public function getPositionInQueue() {
        global $database;
        return $database->getNumberInLine($this->id);
    }
    
}

?>
