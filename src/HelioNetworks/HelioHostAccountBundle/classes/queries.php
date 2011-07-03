<?php

/******************************************\
| cPanel Scripts Suite                     |
| (C) Copyright Bluestone Coding 2005-2010 |
|******************************************|
|           __    ___ ___  ___             |
|          /++\  | _ ) __|/ __|            |
|          \++/  | _ \__ \ (__             |
|           \/   |___/___/\___|            |
|                                          |
|******************************************|
| Suite Version 4.0                        |
| File last updated: 9/14/2010             |
| MySQL DATABASE CLASS                     |
\******************************************/

class queries extends database {

	private function getRowsFromResult($result) {
		$rows = array();
		while($r = $this->getRowFromResult($result)) $rows[] = $r;
		return $rows;
	}

	private function addWhere($query, $where) {
		return $query . " WHERE " . $where;
	}

    private function addOrderBy($query, $sort = array()) {
        return $query . " ORDER BY {$sort[0]} {$sort[1]}";
    }

    private function addLimit($query, $limit) {
        if (is_array($limit)) return $query . " LIMIT {$limit[0]}, {$limit[1]}";
        return $query . " LIMIT {$limit}";
    }

	private function arrayReturnParse($result,$key='',$element='') {
		while($r = $this->getRowFromResult($result)) {
        	if($key) {
				if($element) $return[$r[$key]] = $r[$element];
				else $return[$r[$key]] = $r;
			} else {
				if($element) $return[] = $r[$element];
                else $return[] = $r;
			}
    	}
    	return $return;
	}

	public function getConfig($where='') {
		$query = "SELECT * FROM settings";
		if ($where) $query = $this->addWhere($query, $where);
		$result = $this->query($query);
		return $this->arrayReturnParse($result, 'setting', 'value');
	}

	public function getPlan($where='') {
		$query = "SELECT p.*, p.id as pid, s.*, s.id as sid FROM plans p LEFT JOIN servers s ON p.server = s.id";
		if ($where) $query = $this->addWhere($query, $where);
		$query .= " LIMIT 1";
        $result = $this->query($query);
        return $this->getRowFromResult($result);
	}

	public function getPlanAndAuth($where='') {
		$query = "SELECT p.*, p.id as pid, s.*, s.id as sid, a.state
		          FROM accounts a
		          LEFT JOIN plans p ON p.id = a.plan
		          LEFT JOIN servers s ON s.id = p.server";
		if ($where) $query = $this->addWhere($query, $where);
		$result = $this->query($query);
		return $this->getRowFromResult($result);
	}

    public function getServer($where='') {
        $query = "SELECT *, id as sid FROM servers";
        if ($where) $query = $this->addWhere($query, $where);
        $query .= " LIMIT 1";
        $result = $this->query($query);
        return $this->getRowFromResult($result);
    }

	public function insertCaptcha($answer) {
		$this->query("INSERT INTO captcha (answer) VALUES ($answer)");
		$result = $this->query("SELECT id FROM captcha WHERE answer = {$answer}");
		return $this->getRowFromResult($result);
	}

	public function checkCaptcha($id) {
		$query = "SELECT answer FROM captcha WHERE id = {$id}";
		$result = $this->query($query);
		return $this->getRowFromResult($result);
	}

	public function getTodayRegs() {
		$query = "SELECT COUNT(id) as count FROM accounts WHERE reg_date='".date("Y-m-d")."'";
		$result = $this->query($query);
		return $this->getRowFromResult($result);
	}

	public function checkUsernameAndDomain($username='',$domain='') {
		$query = "SELECT username, domain FROM accounts WHERE username='{$username}' OR domain='{$domain}' LIMIT 1";
		$result = $this->query($query);
		return $this->getRowFromResult($result);
	}

	public function checkEmail($email='') {
		$query = "SELECT email FROM accounts WHERE email='{$email}' LIMIT 1";
		$result = $this->query($query);
		return $this->getRowFromResult($result);
	}

	public function checkEmailAndIP($email='',$ip='') {
		$query = "SELECT email FROM accounts WHERE email='{$email}' OR ip='{$ip}' LIMIT 1";
		$result = $this->query($query);
		return $this->getRowFromResult($result);
	}

	public function addAcct($account=null) {
		if (!is_object($account)) return;
		if ($account->hasAuth()) $query = "UPDATE accounts SET `username`='".$account->username."', `password`='".$account->getPass()."', `email`='".$account->email."', `reg_date`=NOW(), `admin_bypass`=".$account->admin_bypass.", `exp_date`=".$account->exp_date.", `domain`='".$account->domain."', `ip`='".$account->ip."', `plan`='".$account->plan->id."', `state`='".$account->state."', last_update=NOW(), hnname='".$account->hnname."' WHERE auth_key='".$account->getAuth()."'";
        else $query = "INSERT INTO accounts (`username`, `password`, `email`, `reg_date`, `admin_bypass`, `exp_date`, `domain`, `ip`, `state`, `plan`, `auth_key`, `last_update`, `hnname`) VALUES ('".$account->username."', '".$account->getPass()."', '".$account->email."', NOW(), ".$account->admin_bypass.", ".$account->exp_date.", '".$account->domain."', '".$account->ip."', '".$account->state."', '".$account->plan->id."', 'NA', NOW(), '".$account->hnname."')";
        $this->query($query);
	}

	public function moderatorAuthenticate($username='', $password='') {
		$query = "SELECT id, username, permissions FROM moderators WHERE username='{$username}' AND password='{$password}' LIMIT 1";
		$result = $this->query($query);
		return $this->getRowFromResult($result);
	}

    public function sessionAuthenticate($token='') {
		$query = "SELECT m.id, m.username, m.permissions, s.* FROM sessions s LEFT JOIN moderators m ON m.id = s.moderator WHERE s.token='{$token}' LIMIT 1";
		$result = $this->query($query);
		return $this->getRowFromResult($result);
	}

    public function newModeratorPassword($username='', $password='') {
        $query = "UPDATE moderators SET password='{$password}' WHERE username='{$username}'";
        $this->query($query);
    }

    public function addSession($moderator='', $ip='', $time=0, $token='') {
        $query = "INSERT INTO sessions VALUES ('{$token}', {$moderator}, '{$ip}', {$time})";
        $this->query($query);
    }

    public function updateSession($token='', $time=0) {
        $query = "UPDATE sessions SET last_update={$time} WHERE token='{$token}'";
        $this->query($query);
    }

	public function getQueuedAccount($host="") {
		$query = "SELECT p.id as pid, p.*, p.plan, s.id as sid, s.*, a.*
		          FROM accounts a
		          LEFT JOIN plans p ON a.plan = p.id
		          LEFT JOIN servers s ON p.server = s.id
		          WHERE a.state='QUEUED' AND s.host = '{$host}'
		          ORDER BY a.admin_bypass DESC, a.id ASC
		          LIMIT 1";
		$result = $this->query($query);
		return $this->getRowFromResult($result);
	}

	public function removeAccount($id=0) {
		$query = "DELETE FROM accounts WHERE id=".$id." LIMIT 1";
		$this->query($query);
	}

	public function clearAccount($id=0) {
		$query = "UPDATE accounts SET `username`='', `password`='', `reg_date`=NULL, `exp_date`=NULL, `domain`='', `email`='', state='OPEN', `ip`='' WHERE id=".$id." LIMIT 1";
		$this->query($query);
	}

	public function setState($id=0, $state="ACTIVE") {
		$query = "UPDATE accounts SET state='".$state."' WHERE id=".$id;
		$this->query($query);
	}

	public function setStateAndHash($id=0, $passhash='', $state="ACTIVE") {
		$query = "UPDATE accounts SET state='".$state."', password='".$passhash."' WHERE id=".$id;
		$this->query($query);
	}

    public function setStateAndReason($id=0, $state="ACTIVE", $reason='') {
		$query = "UPDATE accounts SET state='{$state}', reason='{$reason}' WHERE id={$id}";
		$this->query($query);
    }

	public function cPanelVisit($username='', $email='') {
		$query = "UPDATE accounts SET last_update=NOW()" . ($email ? ", email='{$email}'" : "") . " WHERE username='{$username}'";
		$this->query($query);
	}

	public function setInactive($plan, $username='', $email='') {

	    if(!in_array(substr($plan->activity_interval, 0, 1), array('|', '0', false))) list($activityNumber, $activityInterval) = explode('|', $plan->activity_interval);

		if($activityNumber && $activityInterval){
    		$query = "UPDATE accounts SET last_update=SUBDATE(SUBDATE(DATE(NOW()), INTERVAL {$activityNumber} {$activityInterval}), INTERVAL 14 DAY) " . ($email ? ", email='{$email}'" : "") . " WHERE username='{$username}'";
    		$this->query($query);
		}
	}

	public function getPlans($where='', $limit=0) {
		$query = "SELECT s.id as sid, p.id as pid, s.*, p.* FROM plans p LEFT JOIN servers s ON s.id = p.server";
        if ($where) $query = $this->addWhere($query, $where);
		if ($limit) $query = $this->addLimit($query, 1);
        $result = $this->query($query);
		if (is_array($limit) OR $limit == 0 OR $limit > 1) return $this->getRowsFromResult($result);
        else return $this->getRowFromResult($result);
	}

	public function getDaemonPlans($host="") {
		$query = "SELECT p.*, p.id as pid, s.*, s.id as sid FROM plans p LEFT JOIN servers s ON s.id = p.server
		          WHERE s.host = '{$host}' AND
                        ((SUBSTRING(p.expiration FROM 1 FOR 1) != '|' AND
                          SUBSTRING(p.expiration FROM 1 FOR 1) != '0' AND
                          SUBSTRING(p.expiration FROM 1 FOR 1) != '') OR
                         (SUBSTRING(p.activity_interval FROM 1 FOR 1) != '|' AND
                          SUBSTRING(p.activity_interval FROM 1 FOR 1) != '0' AND
                          SUBSTRING(p.activity_interval FROM 1 FOR 1) != ''))";
		$result = $this->query($query);
		return $this->getRowsFromResult($result);
	}

	public function getAccountsForNotification($plan, $limit=0) {
		// Do we need to check for expiration date?
		if(!in_array(substr($plan->expiration, 0, 1), array('|', '0', false))) $checkForExp = true;
		// Do we need to check for activity interval?
		if(!in_array(substr($plan->activity_interval, 0, 1), array('|', '0', false))) list($activityNumber, $activityInterval) = explode('|', $plan->activity_interval);
		// Build the query
		if ($checkForExp && $activityInterval) $query = "SELECT * FROM accounts WHERE plan = {$plan->id} (exp_date = ADDDATE(DATE(NOW()), INTERVAL 7 DAY) OR ADDDATE(last_update, INTERVAL {$activityNumber} {$activityInterval}) = ADDDATE(DATE(NOW()), INTERVAL 7 DAY))";
		else if ($checkForExp) $query = "SELECT * FROM accounts WHERE plan = {$plan->id} AND exp_date = ADDDATE(DATE(NOW()), INTERVAL 7 DAY)";
		else if ($activityInterval) $query = "SELECT * FROM accounts WHERE plan = {$plan->id} AND ADDDATE(last_update, INTERVAL {$activityNumber} {$activityInterval}) = ADDDATE(DATE(NOW()), INTERVAL 7 DAY)";
		else return $accounts;
		// Limit?
		if($limit > 0) $query .= " LIMIT " . $limit;
		// Execute the query
		$result = $this->query($query);
		return $this->getRowsFromResult($result);
	}

	public function getAccountsForSuspension($plan, $limit=0) {
		// Do we need to check for expiration date?
		if(!in_array(substr($plan->expiration, 0, 1), array('|', '0', false))) $checkForExp = true;
		// Do we need to check for activity interval?
		if(!in_array(substr($plan->activity_interval, 0, 1), array('|', '0', false))) list($activityNumber, $activityInterval) = explode('|', $plan->activity_interval);
		// Build the query
		if ($checkForExp && $activityInterval) $query = "SELECT * FROM accounts WHERE plan = {$plan->id} AND state='ACTIVE' AND (exp_date <= DATE(NOW()) OR ADDDATE(last_update, INTERVAL {$activityNumber} {$activityInterval}) <= DATE(NOW()))";
		else if ($checkForExp) $query = "SELECT * FROM accounts WHERE plan = {$plan->id} AND state='ACTIVE' AND exp_date <= DATE(NOW())";
		else if ($activityInterval) $query = "SELECT * FROM accounts WHERE plan = {$plan->id} AND state='ACTIVE' AND ADDDATE(last_update, INTERVAL {$activityNumber} {$activityInterval}) <= DATE(NOW())";
		else return $accounts;
		// Limit?
		if($limit > 0) $query .= " LIMIT " . $limit;
		// Execute the query
		$result = $this->query($query);
		return $this->getRowsFromResult($result);
	}

	public function getAccountsForDeletion($plan, $limit=0) {
		// Do we need to check for expiration date?
		if (!in_array(substr($plan->expiration, 0, 1), array('|', '0', false))) $checkForExp = true;
		// Do we need to check for activity interval?
		if (!in_array(substr($plan->activity_interval, 0, 1), array('|', '0', false))) list($activityNumber, $activityInterval) = explode('|', $plan->activity_interval);
		// Build the query
		if ($checkForExp && $activityInterval) $query = "SELECT * FROM accounts WHERE plan = {$plan->id} AND (ADDDATE(exp_date, INTERVAL 7 DAY) <= NOW() OR ADDDATE(ADDDATE(last_update, INTERVAL {$activityNumber} {$activityInterval}), INTERVAL 7 DAY) <= NOW())";
		else if ($checkForExp) $query = "SELECT * FROM accounts WHERE plan = {$plan->id} AND ADDDATE(exp_date, INTERVAL 7 DAY) <= NOW()";
		else if ($activityInterval) $query = "SELECT * FROM accounts WHERE plan = {$plan->id} AND ADDDATE(ADDDATE(last_update, INTERVAL {$activityNumber} {$activityInterval}), INTERVAL 7 DAY) <= NOW()";
		else return $accounts;
		// Limit?
		if ($limit > 0) $query .= " LIMIT " . $limit;
		// Execute the query
		$result = $this->query($query);
		return $this->getRowsFromResult($result);
	}

	public function getAccounts($where='') {
		$query = "SELECT * FROM accounts";
		if ($where) $query = $this->addWhere($query, $where);
		$result = $this->query($query);
		return $this->getRowsFromResult($result);
	}

    public function getAccountsForACP($where='', $sort=array(), $limit=array()) {
        $query = "SELECT p.*, a.*, p.plan AS plan_name, p.id as pid FROM accounts a LEFT JOIN plans p ON p.id = a.plan";
        if ($where) $query = $this->addWhere($query, $where);
        if ($sort) $query = $this->addOrderBy($query, $sort);
        if ($limit) $query = $this->addLimit($query, $limit);
        $result = $this->query($query);
        return $this->getRowsFromResult($result);
    }

    public function getAccountCountForACP($where='') {
        $query = "SELECT COUNT(a.id) AS count FROM accounts a LEFT JOIN plans p ON p.id = a.plan";
        if ($where) $query = $this->addWhere($query, $where);
        $result = $this->query($query);
        return $this->getRowFromResult($result);
    }

	public function getInactive($username='') {
		$query = "SELECT * FROM accounts WHERE state='INACTIVE' AND username='{$username}'";
		$result = $this->query($query);
		return $this->getRowFromResult($result);
	}

	public function getAccount($where='') {
		$query = "SELECT * FROM accounts";
		if ($where) $query = $this->addWhere($query, $where);
		$query .= " LIMIT 1";
		$result = $this->query($query);
		return $this->getRowFromResult($result);
	}

    public function updateAccount($id='', $values=array()) {
        $query = "UPDATE accounts SET ";
        $first_done = false;
        foreach ($values as $key => $value) {
            if (!$first_done) {
                if ($value != "NULL") $query .= $key . '="' . $value . '"';
                else $query .= $key . '=' . $value;
                $first_done = true;
            } else if ($value != "NULL") $query .= ', ' . $key . '="' . $value . '"';
            else $query .= ', ' . $key . '=' . $value;
        }
        $query = $this->addWhere($query, "id = {$id}");
        $this->query($query);
    }

    public function getPlanForAccount($id='') {
        $query = "SELECT p.* FROM accounts a LEFT JOIN plans p ON p.id = a.plan";
        if ($id != '') $query = $this->addWhere($query, "a.id = " . $id);
        $query .= " LIMIT 1";
        $result = $this->query($query);
        return $this->getRowFromResult($result);
    }

    public function getNumberInLine($id=0) {
        $query = "SELECT COUNT(id) as number_in_line FROM accounts WHERE state='QUEUED' AND id <= {$id}";
        $result = $this->query($query);
        $row = $this->getRowFromResult($result);
        return $row['number_in_line'];
    }

}

?>
