<?php
namespace HelioNetworks\FileManagerBundle\Form;

use Gaufrette\Filesystem\Filesystem;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Form;

class RenameFileFormHandler
{
    protected $form;
    protected $request;
    protected $filesystem;

    public function __construct(Form $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
        $this->filesystem = $filesystem;
    }

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
        $filesystem->rename($this->getSource(), $this->getDestination());
    }
}