<?php

namespace HelioNetworks\HelioPanelBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('username');
        $builder->add('password', 'password');
        $builder->add('server', 'entity', array(
            'class' => 'HelioNetworks\HelioPanelBundle\Entity\Account',
            'choices' => $options['servers'],
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'HelioNetworks\HelioPanelBundle\Entity\Account',
            'servers' => null,
        );
    }

    public function getName()
    {
        return 'account';
    }
}
