<?php
namespace HelioNetworks\FileManagerBundle\Form;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Form;

class RenameFileFormHandle
{
    protected $form;
    protected $request;

    public function __construct(Form $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
    }

    public function process()
    {

    }
}