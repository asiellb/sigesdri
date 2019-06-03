<?php

namespace DRI\ExitBundle\Form;

use DRI\ExitBundle\Entity\Mission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;

use DRI\ExitBundle\Entity\Economic;
use DRI\ExitBundle\Entity\Application;
use DRI\UsefulBundle\Form\DatePickerType;

class MissionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('country', EntityType::class, array(
                'class' => 'DRIUsefulBundle:Country',
                'choice_label' => 'spName',
                'choice_value' => 'iso3',
                'attr' => [
                    'class' => 'country_list_mission'
                ],
                'empty_data' => null,
                'required' => false,
            ))
            ->add('provinceCountry', TextType::class, array(
                'attr' => [
                    'placeholder' => 'Provincia',
                ]
            ))
            ->add('institution', TextType::class, array(
                'attr' => [
                    'placeholder' => 'Institución',
                ]
            ))
            ->add('personWhoInvitesName', TextType::class, array(
                'attr' => [
                    'placeholder' => 'Nombre de la Persona que invita',
                ]
            ))
            ->add('personWhoInvitesPosition', TextType::class, array(
                'attr' => [
                    'placeholder' => 'Cargo de la Persona que invita',
                ]
            ))
            ->add('fromDate', DatePickerType::class, [
                'attr' => [
                    'class' => 'date-picker fromDate'
                ],
            ])
            ->add('untilDate', DatePickerType::class, [
                'attr' => [
                    'class' => 'date-picker untilDate'
                ],
            ])
            ->add('timeAmount', TextType::class, array(
                'attr' => [
                    'placeholder' => 'Cantidad de tiempo de la estancia',
                    'readonly' => 'readonly',
                    'class' => 'timeAmount '
                ]
            ))
            ->add('concept', ChoiceType::class, [
                'choices'   => Mission::MISSION_CONCEPT_CHOICE,
                'placeholder' => 'Seleccione el Concepto de Salida',
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('objetives', TextareaType::class, array(
                'attr' => [
                    'placeholder' => 'Objetivos de la Misión',
                ]
            ))
            ->add('workPlanSynthesisFile', VichFileType::class, array(
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
            ))
            ->add('monthlyPay', NumberType::class, array(
                'attr' => [
                    'placeholder' => 'Salario mensual que se recibirá',
                ],
                'required' => false,
            ))
            ->add('totalPay', NumberType::class, array(
                'attr' => [
                    'placeholder' => 'Salario total que se recibirá',
                ],
                'required' => false,
            ))
            ->add('economics', CollectionType::class, array(
                'label' => false,
                'entry_type' => EconomicType::class,

                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        //'class' => '', // we want to use 'tr.item' as collection elements' selector
                    ],
                ],
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'required'      => false,
                'by_reference'  => false,
                'delete_empty'  => true,
                'prototype_name' => '__children_name__',
                'attr' => [
                    'class' => 'collection-economics',
                ],
            ))
            //->add('approved')
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Mission::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mission_type';
    }


}
