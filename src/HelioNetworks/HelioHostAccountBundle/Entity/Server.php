<?php

namespace HelioNetworks\HelioHostAccountBundle\Entity;

class Server {

    public $id;             // Server's ID
    public $host;           // The hostname of the server
    public $user;           // The cPanel user (if applicable) of this server
    public $hash;           // The authentication hash of this server
    public $ssl;            // Should we use SSL/TLS for authentication?
    public $cpanel;         // Does this server use cPanel?
    public $bihourly_limit; // Amount of accounts to create per bihourly cron run

    public function __construct($array=null){
        global $database;
        if (!is_array($array))
            $array = $database->getServer("host='" . gethostname() . "'");
        $this->id             = $array['sid'];
        $this->host           = $array['host'];
        $this->user           = $array['user'];
        $this->hash           = $array['hash'];
        $this->ssl            = $array['ssl'];
        $this->cpanel         = $array['cpanel'];
        $this->bihourly_limit = $array['bihourly_limit'];
    }

    public static function serverFromID($id) {
        global $database;
        $id = intval($id);
        $row = $database->getServer("id = {$id}");
        $server = new server($row);
        return $server;
    }

    public function getAcctsFromCP() {
        $xmlapi = new xmlapi($this->host);
        $xmlapi->hash_auth($this->user, $this->hash);
        $object = $xmlapi->listaccts();
        return $object->acct;
    }

    public function compare($otherServer) {
        if (!is_object($otherServer)) return false;
        if ($otherServer->host != $this->host) return false;
        if ($otherServer->user != $this->user) return false;
        return true;
    }

    public function getQueuedAccount() {
        global $database, $info;
        // Get an account
        $array = $database->getQueuedAccount($this->host);
        $account = new account;
        $account->accountFromRow($array);
        // Set password correctly
        $account->setPass($array["password"]);
        return $account;
    }

    public function getDaemonPlans() {
        global $database;
        return plan::processPlanArrays($database->getDaemonPlans($this->host));
    }

}