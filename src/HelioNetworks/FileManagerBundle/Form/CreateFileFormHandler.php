<?php
namespace HelioNetworks\FileManagerBundle\Form;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Form;

class CreateFileFormHandler
{
    protected $form;
    protected $request;

    public function __construct(Form $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
    }

    public function getFilename()
    {
        return $this->form->getData()->filename;
    }

    public function process()
    {
        $handle = fopen($this->getFilename(), 'w');
        fclose($handle);
    }
}