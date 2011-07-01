<?php

namespace HelioNetworks\FileSystemBundle\Exception;

class SecurityBreachException extends \RuntimeException
{
    public function __construct()
    {
        return parent::__construct('Security breach detected!');
    }
}