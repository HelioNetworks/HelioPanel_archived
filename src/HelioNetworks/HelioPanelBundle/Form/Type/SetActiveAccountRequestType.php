<?php

namespace HelioNetworks\HelioPanelBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use HelioNetworks\HelioPanelBundle\Entity\User;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class SetActiveAccountRequestType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		global $options;

		$builder->add('activeAccount', 'entity', array(
			'class' => 'HelioNetworks\HelioPanelBundle\Entity\Account',
			'query_builder' => function(EntityRepository $er) {
				global $options;

				$qb = $er->createQueryBuilder('a');
				if ($options['user']) {
					$qb->where('a.user = :user')
						->setParameter(':user', $options['user']->getId());
				}

				return $qb;
			},
		));
	}

	public function getDefaultOptions(array $options)
	{
		return array(
			'data_class' => 'HelioNetworks\HelioPanelBundle\Form\Model\SetActiveAccountRequest',
			'user' => null,
		);
	}

	public function getName()
	{
		return 'account';
	}
}