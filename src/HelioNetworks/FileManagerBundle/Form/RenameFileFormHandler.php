<?php
namespace HelioNetworks\FileManagerBundle\Form;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Form;

class RenameFileFormHandler
{
    protected $form;
    protected $request;

    public function __construct(Form $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
    }

    public function getSource()
    {
        return $this->form->getData()->source;
    }

    public function getDestination()
    {
        return $this->form->getData()->destination;
    }

    public function process()
    {
        rename($this->getSource(), $this->getDestination());
    }
}