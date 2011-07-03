<?php

namespace HelioNetworks\HelioHostAccountBundle\Repository;

class AccountRepository extends Repository
{
    public function findByName($name)
    {
        $name = mysql_escape_string($name);
		$query = "SELECT * FROM accounts WHERE username='{$name}'";
		$result = $this->query($query);

        //TODO: Fix the account class and use it instead.
		$account = new \stdClass();
        $account->id           = $result['id'];
        $account->username     = $result['username'];
        $account->domain       = $result['domain'];
        $account->passhash     = $result['password'];
        $account->email        = $result['email'];
        $account->auth_key     = $result['auth_key'];
        $account->reg_date     = $result['reg_date'];
        $account->exp_date     = $result['exp_date'];
        $account->state        = $result['state'];
        $account->ip           = $result['ip'];
        $account->last_update  = $result['last_update'];
        $account->reason       = $result['reason'];
        $account->hnname       = $result['hnname'];
        $account->admin_bypass = $result['admin_bypass'];

        return $account;
    }
}
