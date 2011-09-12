<?php

namespace HelioNetworks\FileManagerBundle\Hook;

use HelioNetworks\HelioPanelBundle\Hook\HookSectionInterface;

class RemoveSection implements HookSectionInterface
{
    public function getName()
    {
        return 'remove($source)';
    }

    public function getCode()
    {
        return <<<'PHP'
    return unlink(dirname(__DIR__).'/'.$source);
PHP;
    }
}
