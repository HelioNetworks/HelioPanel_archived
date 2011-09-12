<?php

namespace HelioNetworks\FileManagerBundle\Hook;

use HelioNetworks\HelioPanelBundle\Hook\HookSectionInterface;

class CreateSection implements HookSectionInterface
{
    public function getName()
    {
        return 'create($source)';
    }

    public function getCode()
    {
        return <<<'PHP'
return file_put_contents(dirname(__DIR__).'/'.$source, '');
PHP;
    }
}
