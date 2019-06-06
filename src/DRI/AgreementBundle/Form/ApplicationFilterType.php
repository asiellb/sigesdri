<?php

namespace DRI\AgreementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class ApplicationFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('number', Filters\TextFilterType::class)
            ->add('numberSlug', Filters\TextFilterType::class)
            ->add('background', Filters\TextFilterType::class)
            ->add('objetives', Filters\TextFilterType::class)
            ->add('basement', Filters\TextFilterType::class)
            ->add('commitments', Filters\TextFilterType::class)
            ->add('validity', Filters\TextFilterType::class)
            ->add('memberForCubanPart', Filters\TextFilterType::class)
            ->add('memberForForeignPart', Filters\TextFilterType::class)
            ->add('results', Filters\TextFilterType::class)
            ->add('expenses', Filters\NumberFilterType::class)
            ->add('state', Filters\TextFilterType::class)
            ->add('confirmDate', Filters\DateTimeFilterType::class)
            ->add('rejectDate', Filters\DateTimeFilterType::class)
            ->add('rejectReasons', Filters\TextFilterType::class)
            ->add('closed', Filters\BooleanFilterType::class)
            ->add('used', Filters\BooleanFilterType::class)
            ->add('createdAt', Filters\DateTimeFilterType::class)
            ->add('updatedAt', Filters\DateTimeFilterType::class)        
                    ->add('institution', Filters\EntityFilterType::class, array(
                    'class' => 'DRI\AgreementBundle\Entity\Institution',
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
                    ->add('institutional', Filters\EntityFilterType::class, array(
                    'class' => 'DRI\AgreementBundle\Entity\Institutional',
                    'choice_label' => 'number',
                )) 
        
        ;


    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }

    public function getBlockPrefix()
    {
        return 'dri_agreementbundle_applicationfiltertype';
    }

}
