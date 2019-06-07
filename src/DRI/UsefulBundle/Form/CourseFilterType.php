<?php

namespace DRI\UsefulBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;

class CourseFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('name', Filters\TextFilterType::class)
            ->add('coordinator', Filters\TextFilterType::class)
        
            ->add('area', Filters\EntityFilterType::class, array(
                    'class' => 'DRI\UsefulBundle\Entity\Area',
                    'choice_label' => 'name',
            )) 
        ;
        $builder->setMethod("GET");


    }

    /**
     * @return string|null
     */
    public function getBlockPrefix()
    {
        return null;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}
