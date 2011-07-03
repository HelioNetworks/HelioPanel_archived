<?php

namespace HelioNetworks\HelioHostAccountBundle\Entity;

class Plan {

	public $id;		           // Plan's ID
	public $name;              // Plan's Name
	public $server;            // The server that this plan is on
	public $package;           // Associated cPanel Package
	public $auth;              // Authorization Key Needed?
	public $limit;	           // Limit of Signups Per Day
	public $validation;	       // Validation By Admin Needed?
	public $expiration;	       // Account Expiration String
	public $duplicates;	       // Are duplicate accounts from a single IP/email allowed?
	public $disperse;	       // Queue account creation?
	public $activity_interval; // Interval this acount can go inactive before suspension
    public $bihourly_suspend;  // Amount of accounts to suspend per bihourly cron run
    public $bihourly_delete;   // Amount of accounts to delete per bihourly cron run

	public function __construct($array){
		$this->id                = $array['pid'];
		$this->name              = $array['plan'];
		$this->package           = $array['package'];
		$this->auth              = $array['auth'];
		$this->limit             = $array['limit'];
		$this->validation        = $array['validation'];
		$this->expiration        = $array['expiration'];
		$this->duplicates        = $array['allow_duplicates'];
		$this->disperse	         = $array['disperse'];
		$this->activity_interval = $array['activity_interval'];
        $this->bihourly_suspend  = $array['bihourly_suspend'];
        $this->bihourly_delete   = $array['bihourly_delete'];
        $this->server            = new server($array);
	}

	public static function processPlanArrays($arrays) {
		$plans = array();
		foreach($arrays as $array) {
			$array['pid'] = $array['id'];
			$plans[] = new plan($array);
		}
		return $plans;
	}

	private function processAccountArrays($arrays) {
		$accounts = array();
		foreach($arrays as $array) {
			$account = new account;
			// Give it some values
			$account->accountFromRow($array);
			$account->plan = $this;
			$accounts[] = $account;
		}
		return $accounts;
	}

	public static function getPlans() {
		global $database;
		return self::processPlanArrays($database->getPlans());
	}

    public static function getPlan($where='') {
        global $database;
        $array = $database->getPlan($where);
        return new plan($array);
    }

	public function getAccountsForNotification($limit=0) {
		global $database;
		return $this->processAccountArrays($database->getAccountsForNotification($this, $limit));
	}

	public function getAccountsForSuspension($limit=0) {
		global $database;
		return $this->processAccountArrays($database->getAccountsForSuspension($this, $limit));
	}

	public function getAccountsForDeletion($limit=0) {
		global $database;
		return $this->processAccountArrays($database->getAccountsForDeletion($this, $limit));
	}

}
