<?php

namespace HelioNetworks\HelioPanelBundle\Form\Type;

use HelioNetworks\HelioPanelBundle\Entity\Account;
use Doctrine\ORM\EntityRepository;
use HelioNetworks\HelioPanelBundle\Entity\User;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class SetActiveAccountRequestType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('activeAccount', 'entity', array(
            'class' => 'HelioNetworks\HelioPanelBundle\Entity\Account',
            'choices' => $options['accounts'],
            'preferred_choices' => array($options['current_account']->getId()),
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'HelioNetworks\HelioPanelBundle\Form\Model\SetActiveAccountRequest',
            'accounts' => array(),
            'current_account' => new Account(),
        );
    }

    public function getName()
    {
        return 'account_set_active';
    }
}
