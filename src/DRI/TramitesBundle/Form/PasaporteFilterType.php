<?php

namespace DRI\TramitesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class PasaporteFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('noPas', Filters\TextFilterType::class)
            ->add('fechaExp', Filters\DateFilterType::class)
            ->add('fechaVen', Filters\DateFilterType::class)
            ->add('tipoPas', Filters\TextFilterType::class)
            ->add('estadoPas', Filters\TextFilterType::class)
            ->add('causaBaja', Filters\TextFilterType::class)        
                    ->add('titular', Filters\EntityFilterType::class, array(
                    'class' => 'DRI\ClienteBundle\Entity\Persona',
                    'choice_label' => 'ci',
                )) 
        
        ;


    }

    public function getBlockPrefix()
    {
        return 'dri_tramitesbundle_pasaportefiltertype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}
