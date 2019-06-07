<?php

namespace DRI\UsefulBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use DRI\UsefulBundle\Entity\Course;

class CourseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type', ChoiceType::class, [
                'label' => 'Tipo de Pasaporte',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => array(
                    'Doctorado'     =>'DOC',
                    'Maestría'      =>'MAE',
                    'Especialidad'  =>'ESP',
                ),
                'attr' => array(
                    'class'=>'radio-list',
                ),
                'choices_as_values' => true,
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'icheck', 'data-title' => $key];
                },
                'multiple'=> false,
                'expanded'=> true,
                'required' => true,
            ])
            ->add('coordinator')
            ->add('area', EntityType::class, array(
                'class' => 'DRI\UsefulBundle\Entity\Area',
                'choice_label' => 'name',
                'placeholder' => 'Please choose',
                'empty_data' => null,
                'required' => false
 
            )) 
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Course::class
        ));
    }
}
