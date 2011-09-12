<?php

namespace HelioNetworks\HelioPanelBundle\Form\Model;

use HelioNetworks\HelioPanelBundle\Entity\Account;

class SetActiveAccountRequest
{
    protected $activeAccount;

    public function getActiveAccount()
    {
        return $this->activeAccount;
    }

    public function setActiveAccount(Account $activeAccount)
    {
        $this->activeAccount = $activeAccount;
    }
}
