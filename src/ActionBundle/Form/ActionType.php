<?php

namespace ActionBundle\Form;

use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ActionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('notes')
            ->add('startedAt', DateTimeType::class, array(
                'widget' => 'single_text',
                'label' => 'Start at',
                'format' => 'dd.MM.yyyy HH:mm',
                'model_timezone' => 'Europe/Oslo',
                'view_timezone' => 'Europe/Oslo'
            ))
            ->add('done')
            ->add('actionType', EntityType::class, array(
                'class' => 'ActionBundle\Entity\ActionType',
                'choice_label' => 'name'
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ActionBundle\Entity\Action'
        ));
    }
}
