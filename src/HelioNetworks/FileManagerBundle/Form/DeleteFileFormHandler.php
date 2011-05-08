<?php
namespace HelioNetworks\FileManagerBundle\Form;

use Gaufrette\Filesystem\Filesystem;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Form;

class DeleteFileFormHandler extends FormHandler
{
    public function getFilename()
    {
        return $this->form->getData()->filename;
    }

    public function process(Filesystem $filesystem)
    {
        if($this->isValid()) {
            return $filesystem->delete($this->getFilename());
        }
        else {
            return false;
        }
    }
}