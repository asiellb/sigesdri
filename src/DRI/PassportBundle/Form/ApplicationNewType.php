<?php

namespace DRI\PassportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use DRI\UsefulBundle\Form\DatePickerType;

class ApplicationNewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('applicationReason', ChoiceType::class, [
                'label' => 'Razón de Solicitud',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => array(
                    'Confección'       =>'CON',
                    'Prórroga'    =>'PRO'
                ),
                'attr' => array(
                    'class'=>'radio-list',
                ),
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'icheck', 'data-title' => $key];
                },
                'expanded'=> true,
            ])
            ->add('client', EntityType::class, [
                'label' => 'Cliente que solicita',
                'class' => 'DRI\ClientBundle\Entity\Client',
                'choice_label' => 'fullName',
                'placeholder' => 'Seleccione un cliente ...',
                'empty_data' => null,
                'required' => false,
                'attr' => [
                    'class' => 'select2'
                ]

            ])
            ->add('applicationDate', DatePickerType::class,[
                'label' => 'Fecha de Solicitud',
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Fecha de Solicitud'
                ],
            ])
            ->add('passportType', ChoiceType::class, [
                'placeholder' => 'Seleccione el tipo de pasaporte ...',
                'label' => 'Tipo de Pasaporte',
                'choices'  => array(
                    'Corriente'     =>'COR',
                    'Diplomático'   =>'DIP',
                    'Oficial'       =>'OFI',
                    'Servicio'      =>'SER',
                    'Marino'        =>'MAR'
                ),
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ])
            ->add('applicationType', ChoiceType::class, [
                'label' => 'Tipo de Solicitud',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => array(
                    'Regular'   =>'REG',
                    'Inmediato' =>'INM'
                ),
                'attr' => array(
                    'class'=>'radio-list',
                ),
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'icheck', 'data-title' => $key];
                },
                'expanded'=> true,
            ])
            ->add('applicantOrgan', TextType::class,[
                'label' => 'Órgano Solicitante',
                'data' => 'Universidad de Camagüey'
            ])
            ->add('travelReason', TextareaType::class,[
                'label' => 'Motivos del Viaje'
            ])
            ->add('state', ChoiceType::class, [
                'placeholder' => 'Seleccione el estado ...',
                'label' => 'Estado de la Solicitud',
                'choices'  => array(
                    'Confeccionada' => 'CON',
                    'Enviada'       => 'ENV',
                    'Confirmada'    => 'CNF',
                    'Rechazada'     => 'REC'
                ),
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ])
            ->add('sendDate', DatePickerType::class,[
                'label' => 'Enviada el',
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Seleccione la fecha de envío'
                ],
                'required' => false
            ])
            ->add('confirmDate', DatePickerType::class,[
                'label' => 'Confirmada el',
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Seleccione la fecha de confirmación'
                ],
                'required' => false
            ])
            ->add('rejectDate', DatePickerType::class,[
                'label' => 'Rechazada el',
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Seleccione la fecha de rechazo'
                ],
                'required' => false
            ])
            ->add('rejectReasons', TextareaType::class,[
                'label' => 'Motivos de Rechazo',
                'required' => false
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DRI\PassportBundle\Entity\Application'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'passportbundle_application';
    }
}
