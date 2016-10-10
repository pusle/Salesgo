<?php

namespace PermitBundle\Form;

use PermitBundle\Entity\PermitCounty;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermitType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('permitType', EntityType::class, array(
                'class' => 'PermitBundle\Entity\PermitType',
                'choice_label' => 'type'
            ))
            ->add('permitNumber')
            ->add('permitCounty', EntityType::class, array(
                'class' => 'PermitBundle\Entity\PermitCounty',
                'choice_label' => 'county'
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PermitBundle\Entity\Permit'
        ));
    }
}
