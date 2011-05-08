<?php
namespace HelioNetworks\FileManagerBundle\Form;

use Gaufrette\Filesystem\Filesystem;

class CreateDirectoryFormHandler extends FormHandler
{
    public function getDirectory()
    {
        return $this->form->getData()->directory;
    }

    public function process(Filesystem $filesystem)
    {
        if ($this->isValid()){
            return $filesystem->write($this->getDirectory().'/empty', '');
        }
        else {
            return false;
        }
    }
}