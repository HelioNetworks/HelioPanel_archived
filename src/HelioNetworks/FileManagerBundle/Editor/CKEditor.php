<?php

namespace HelioNetworks\FileManagerBundle\Editor;

class CKEditor implements EditorInterface
{
    public function getName()
    {
        return 'CKEditor WYSIWYG Editor';
    }

    public function getAssets()
    {
        return array(
            'bundles/helionetworksfilemanager/js/ckeditor/ckeditor.js',
        	'bundles/helionetworksfilemanager/js/ckeditor/adapters/jquery.js',
        	'bundles/helionetworksfilemanager/js/ckeditor.js',
        );
    }
}
