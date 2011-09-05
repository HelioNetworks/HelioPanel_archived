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
        	if(is_file(dirname(__DIR__).'/'.$source.''.$file.'')) {
            	$type = 'file';
            }else{
                $type = 'folder';
            }

            $files[] = array(
            	'path' => str_replace(dirname(__DIR__).'/'.$source, '', dirname(__DIR__).'/'.$source.$file),
                'name' => $file,
                'type' => $type,
            );
        }
    }
    closedir($handle);
}

return $files;
PHP;
	}
}