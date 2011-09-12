<?php

namespace HelioNetworks\FileManagerBundle\Hook;

use HelioNetworks\HelioPanelBundle\Hook\HookSectionInterface;

class ListDirectorySection implements HookSectionInterface
{
    public function getName()
    {
        return 'listDirectory($source)';
    }

    public function getCode()
    {
        return <<<'PHP'
$files = array();
if ($handle = opendir(dirname(__DIR__).'/'.$source)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $files[] = array(
                'source' => str_replace(dirname(__DIR__).'/', '', dirname(__DIR__).'/'.$source.'/'.$file),
                'name' => $file,
                'is_file' => is_file(dirname(__DIR__).'/'.$source.'/'.$file.''),
            );
        }
    }
    closedir($handle);
}

return $files;
PHP;
    }
}
