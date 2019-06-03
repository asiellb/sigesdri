<?php

namespace DRI\AgreementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class InstitutionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('name', Filters\TextFilterType::class)
            ->add('nameSlug', Filters\TextFilterType::class)
            ->add('acronym', Filters\TextFilterType::class)
            ->add('countryState', Filters\TextFilterType::class)
            ->add('province', Filters\TextFilterType::class)
            ->add('city', Filters\TextFilterType::class)
            ->add('logo', Filters\TextFilterType::class)
            ->add('url', Filters\TextFilterType::class)
            ->add('rector', Filters\TextFilterType::class)
            ->add('createdAt', Filters\DateTimeFilterType::class)
            ->add('updatedAt', Filters\DateTimeFilterType::class)        
                    ->add('country', Filters\EntityFilterType::class, array(
                    'class' => 'DRI\UsefulBundle\Entity\Country',
                    'choice_label' => 'name',
                )) 
                    ->add('createdBy', Filters\EntityFilterType::class, array(
                    'class' => 'DRI\UserBundle\Entity\User',
                    'choice_label' => 'firstName',
                )) 
                    ->add('lastUpdateBy', Filters\EntityFilterType::class, array(
                    'class' => 'DRI\UserBundle\Entity\User',
                    'choice_label' => 'firstName',
                )) 
        
        ;


    }

    public function getBlockPrefix()
    {
        return 'dri_agreementbundle_institutionfiltertype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}
