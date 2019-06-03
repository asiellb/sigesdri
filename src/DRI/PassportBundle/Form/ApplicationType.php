<?php

namespace DRI\PassportBundle\Form;

use DRI\ClientBundle\Entity\Client;
use DRI\PassportBundle\Entity\Application;
use DRI\PassportBundle\Entity\Passport;
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

class ApplicationType extends AbstractType
{
    private $currentAction;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->currentAction = $options['currentAction'];

        if(!$options['data']->hasClient()) {
            $builder
                ->add('client', EntityType::class, [
                    'label' => 'Cliente que solicita',
                    'class' => 'DRIClientBundle:Client',
                    'choice_label' => 'fullName',
                    'placeholder' => 'Seleccione un cliente ...',
                    'empty_data' => null,
                    'required' => true,
                    'attr' => [
                        'class' => 'select2'
                    ]

                ]);
        }

        $builder
            ->add('organ', TextType::class,[
                'label' => 'Órgano Solicitante',
                'data' => 'Universidad de Camagüey'
            ])
            ->add('travelReason', TextareaType::class,[
                'label' => 'Motivos del Viaje'
            ])
            ->add('applicationDate', DatePickerType::class,[
                'label' => 'Fecha de Solicitud',
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Fecha de Solicitud'
                ],
            ])
            ->add('reason', ChoiceType::class, [
                'label' => 'Razón de Solicitud',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => Application::PASSPORT_APPLICATION_REASON_CHOICE,
                'attr' => array(
                    'class'=>'radio-list',
                ),
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'icheck', 'data-title' => $key];
                },
                'expanded'=> true,
            ])
            ->add('applicationType', ChoiceType::class, [
                'label' => 'Tipo de Solicitud',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => Application::PASSPORT_APPLICATION_TYPE_CHOICE,
                'attr' => array(
                    'class'=>'radio-list',
                ),
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'icheck', 'data-title' => $key];
                },
                'expanded'=> true,
            ])
            ->add('passportType', ChoiceType::class, [
                'label' => 'Tipo de Pasaporte',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => Passport::PASSPORT_TYPE_CHOICE,
                'attr' => array(
                    'class'=>'radio-list',
                ),
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'icheck', 'data-title' => $key];
                },
                'expanded'=> true,
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'Estado de la Solicitud',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => Application::PASSPORT_APPLICATION_STATE_CHOICE,
                'attr' => array(
                    'class'=>'radio-list',
                ),
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'icheck', 'data-title' => $key];
                },
                'expanded'=> true,
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
            'data_class' => 'DRI\PassportBundle\Entity\Application',
            'currentAction' => null,
        ));

        $resolver->setRequired('currentAction');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'passportbundle_application';
    }
}
