<?php

namespace DRI\UsefulBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class AreaFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('name', Filters\TextFilterType::class)
            ->add('active', Filters\BooleanFilterType::class)
                    ->add('leader', Filters\EntityFilterType::class, array(
                    'class' => 'DRI\ClientBundle\Entity\Cliente',
                    'choice_label' => 'name',
                )) 
        
        ;


    }

    public function getBlockPrefix()
    {
        return 'dri_usefulbundle_areafiltertype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}
