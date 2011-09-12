<?php

namespace HelioNetworks\FileManagerBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class EditFileRequestType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('source');
        $builder->add('data', 'hidden');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'HelioNetworks\FileManagerBundle\Form\Model\FileRequest',
        );
    }

    public function getName()
    {
        return 'edit_file_request';
    }
}
