<?php

namespace DRI\PassportBundle\Form;

use DRI\PassportBundle\Entity\Passport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use DRI\UsefulBundle\Form\DatePickerType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Presta\ImageBundle\Form\Type\ImageType;

use DRI\ClientBundle\Entity\Client;

class PassportType extends AbstractType
{
    private $currentAction;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->currentAction = $options['currentAction'];

        if(!$options['data']->hasHolder()) {
            $builder
                ->add('holder', EntityType::class, [
                    'label' => 'Titular del Pasaporte',
                    'class' => 'DRIClientBundle:Client',
                    'choice_label' => 'fullName',
                    'placeholder' => 'Seleccione el titular ...',
                    'empty_data' => null,
                    'required' => false,
                    'attr' => [
                        'class' => 'select2',
                    ],
                ])
                ->add('clientCi', TextType::class, [
                    'label' => 'CI del Cliente',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Agregue el CI del Cliente',
                    ],
                ]);
            ;
        }
        if(!$options['data']->hasApplication()) {
            $builder
                ->add('application', EntityType::class, [
                    'label' => 'Solicitud',
                    'class' => 'DRIPassportBundle:Application',
                    'choice_label' => 'number',
                    'placeholder' => 'Seleccione la Solicitud ...',
                    'empty_data' => null,
                    'required' => false,
                    'attr' => [
                        'class' => 'select2',
                    ],
                ]);
        }


        $builder
            ->add('number', TextType::class, [
                'label' => 'Número',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Agregue el número de Pasaporte',
                    'class' => 'number-cu',
                    'tabindex' => 1
                ]
            ])
            ->add('issueDate', DatePickerType::class,[
                'label' => 'Fecha de Emisión',
                'attr' => [
                    'class'=>'date-picker first-date',
                    'size'=>'16',
                    'placeholder' => 'Seleccione la fecha de emisión',
                    'tabindex' => 2
                ],
                'required' => true
            ])
            ->add('expiryDate', DatePickerType::class,[
                'label' => 'Fecha de Vencimiento',
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Seleccione la fecha de vencimiento',
                    'tabindex' => 3
                ],
                'required' => true
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Tipo de Pasaporte',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => Passport::PASSPORT_TYPE_CHOICE,
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
            ->add('firstPageFile', ImageType::class, [
                //'aspect_ratios' => [1],
                'cancel_button_class' => 'btn dark btn-outline',
                'save_button_class' => 'btn green',
                'enable_remote' => false,
                'required' => false,
                'max_width' => '600',
                'max_height' => '720',
                'preview_width' => '150',
                'preview_height' => '180',
                'cropper_options' => [
                    //'movable' => false,
                    //'zoomable' => false,
                    //'rotatable' => false,
                    //'scalable' => false
                ]
            ])
        ;

        if (($options['data']->isForExtend() || $options['data']->isRequireExtend()) && !($options['data']->isExpired())){
            $builder
                ->add('firstExtension', CheckboxType::class, array(
                'label' => false,
                'attr' => array(
                    'data-toggle'   => 'toggle',
                    'style'         => 'margin-left: 0px;',
                    'data-on'       => 'Si',
                    'data-off'      => 'No',
                    'data-onstyle'  => 'success',
                    'data-size'     => 'small'
                ),
                'required' => false,
                ))
                ->add('firstExtensionDate', DatePickerType::class,[
                    'label' => 'Fecha de la primera Prórroga',
                    'attr' => [
                        'class'=>'date-picker',
                        'size'=>'16',
                        'placeholder' => 'Seleccione la fecha de la primera Prórroga'
                    ],
                    'required' => false
                ])
                ->add('secondExtension', CheckboxType::class, array(
                'label' => false,
                'attr' => array(
                    'data-toggle'   => 'toggle',
                    'style'         => 'margin-left: 0px;',
                    'data-on'       => 'Si',
                    'data-off'      => 'No',
                    'data-onstyle'  => 'success',
                    'data-size'     => 'small'
                ),
                'required' => false,
                ))
                ->add('secondExtensionDate', DatePickerType::class,[
                    'label' => 'Fecha de la segunda Prórroga',
                    'attr' => [
                        'class'=>'date-picker',
                        'size'=>'16',
                        'placeholder' => 'Seleccione la fecha de la segunda Prórroga'
                    ],
                    'required' => false
                ])
            ;
        }
        if ($options['data']->isExpired() && $this->currentAction == 'edit'){
            $builder
                ->add('drop', CheckboxType::class, array(
                'label' => false,
                'attr' => array(
                    'data-toggle'   => 'toggle',
                    'style'         => 'margin-left: 0px;',
                    'data-on'       => 'Si',
                    'data-off'      => 'No',
                    'data-onstyle'  => 'success',
                    'data-size'     => 'small'
                ),
                'required' => false,
            ))
                ->add('dropReason', TextareaType::class,[
                    'label' => 'Motivos de la Baja',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Detalle los motivos de la Baja'
                    ],
                ])
                ->add('dropDate', DatePickerType::class,[
                    'label' => 'Fecha de Baja',
                    'attr' => [
                        'class'=>'date-picker',
                        'size'=>'16',
                        'placeholder' => 'Seleccione la fecha de Baja'
                    ],
                    'required' => false
                ])
            ;
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DRI\PassportBundle\Entity\Passport',
            'currentAction' => null,
        ));

        $resolver->setRequired('currentAction');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'passportbundle_passport';
    }
}
