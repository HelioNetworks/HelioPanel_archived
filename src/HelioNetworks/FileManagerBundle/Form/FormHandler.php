<?php

namespace HelioNetworks\FileManagerBundle\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

class FormHandler
{
    protected $request;
    protected $form;

    public function __construct(Form $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
    }

    public function isValid()
    {
        if('POST' == $this->request->getMethod()) {
            $this->form->bindRequest($this->request);

            if($this->form->isValid()) {

                return true;
            }
        }

        return false;
    }

}