<?php
namespace HelioNetworks\FileManagerBundle\Form;

use Gaufrette\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

class RenameFileFormHandler extends FormHandler
{
    public function getSource()
    {
        return $this->form->getData()->source;
    }

    public function getDestination()
    {
        return $this->form->getData()->destination;
    }

    public function process(Filesystem $filesystem)
    {
        if($this->isValid()) {
            return $filesystem->rename($this->getSource(), $this->getDestination());
        }
        else {
            return false;
        }
    }
}