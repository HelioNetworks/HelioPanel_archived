<?php

namespace HelioNetworks\FileManagerBundle\Form;

use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Form\AbstractType;

class CreateFileFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('filename');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'HelioNetworks\FileManagerBundle\Form\CreateFile',
        );
    }
}