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

use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Presta\ImageBundle\Form\Type\ImageType;

use DRI\ClientBundle\Entity\Client;

class PassportType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!$options['data']->hasHolder()) {
            $builder->add('holder', EntityType::class, [
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
            ;
        }

        $builder
            ->add('firstPageFile', ImageType::class, [
                //'aspect_ratios' => [1],
                'cancel_button_class' => 'btn dark btn-outline',
                'save_button_class' => 'btn green',
                'enable_remote' => false,
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
            ->add('number', TextType::class, [
                'label' => 'Número',
                'attr' => [
                    'placeholder' => 'Agregue el número de Pasaporte',
                    'class' => 'number-cu',
                    'tabindex' => 1
                ]
            ])
            ->add('issueDate', DatePickerType::class,[
                'label' => 'Fecha de Emisión',
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Seleccione la fecha de emisión',
                    'tabindex' => 2
                ],
                'required' => false
            ])
            ->add('expiryDate', DatePickerType::class,[
                'label' => 'Fecha de Vencimiento',
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Seleccione la fecha de vencimiento',
                    'tabindex' => 3
                ],
                'required' => false
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Tipo de Pasaporte',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => array(
                    'Oficial'    =>'OFI',
                    'Ordinario'   =>'ORD',
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
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'Estado',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => array(
                    'Activo'       =>'ACT',
                    'Vencido'      =>'VEN',
                    'Baja'         =>'BAJ'
                ),
                'attr' => array(
                    'class'=>'radio-list',
                    'tabindex' => 4
                ),
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'icheck', 'data-title' => $key];
                },
                'expanded'=> true,
            ])
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
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DRI\PassportBundle\Entity\Passport'
        ));
    }
}
