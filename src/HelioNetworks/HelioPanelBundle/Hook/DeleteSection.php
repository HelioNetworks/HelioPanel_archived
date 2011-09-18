<?php

namespace HelioNetworks\HelioPanelBundle\Hook;

class DeleteSection implements HookSectionInterface
{
    public function getName()
    {
        return 'delete()';
    }

    public function getCode()
    {
        return <<<'PHP'
unlink(__FILE__);
PHP;
    }
}
