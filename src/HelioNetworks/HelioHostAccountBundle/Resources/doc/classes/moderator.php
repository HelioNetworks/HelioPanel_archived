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
| Suite Version 3.0                        |
| File last updated: 12.23.2009            |
| ACCOUNT MASTER CLASS                     |
\******************************************/

class moderator {
    
    public $id;         // Unique ID
    public $username;   // Moderator username
    public $permissions    = array();
    public static $options = array('accounts',
                                   'plans',
                                   'keys',
                                   'tools',
                                   'config',
                                   'personal');
    
    // Attempt to authenticate moderator w/ password
    public function authPass($user, $password) {
        global $database, $page;
        $hash = self::hash($password);
        $moderator = $database->moderatorAuthenticate($database->escape($user), $database->escape($hash));
        if (!$moderator['id']) $page->error("authfail");
        $this->id = $moderator['id'];
        $this->username = $moderator['username'];
        $this->permissions = explode(",", $moderator['permissions']);
        for ($i = count($this->permissions) - 1; $i >= 0; $i--)
            if (!in_array($this->permissions[$i], self::$options)) array_splice($this->permissions, $i, 1);
        $page->token = $this->generateToken();
        $database->addSession($this->id, $_SERVER['REMOTE_ADDR'], time(), $page->token);
    }
    
    // Attempt to authenticate moderator w/ session token
    public function authSess($token) {
        global $database, $info, $page;
        $moderator = $database->sessionAuthenticate($database->escape($token));
        if (!$moderator['id'] OR $moderator['IP'] != $_SERVER['REMOTE_ADDR']) $page->error("sess_invalid");
        $this->id = $moderator['id'];
        $this->username = $moderator['username'];
        $this->permissions = explode(",", $moderator['permissions']);
        for ($i = count($this->permissions) - 1; $i >= 0; $i--)
            if (!in_array($this->permissions[$i], self::$options)) array_splice($this->permissions, $i, 1);
        $page->token = $moderator['token'];
        if ($moderator['last_update'] < (time() - $info->settings['session_length'])) {
            $page->error("sess_expired", '', array(), true);
            $page->authenticate();
            exit;
        }
        $database->updateSession($page->token, time());
    }
    
    // Generate a new session token
    public function generateToken() {
        // Possible values
        $values = 'abcdefghijklmnopqrstuvwqyz0123456789';
        // Seed generator
        list($usec, $sec) = explode(' ', microtime());
        mt_srand((float) $sec + ((float) $usec * 100000));
        // Generate
        $str = '';
        for ($i = 0; $i < 36; $i++) 
            $str .= $values{mt_rand(0, 35)};
        return $str;
    }
    
    public static function hash($string) {
        return md5(sha1($string . "djbob rocks"));
    }
    
}

?>
