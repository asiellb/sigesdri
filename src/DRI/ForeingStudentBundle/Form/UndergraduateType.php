<?php

namespace DRI\ForeingStudentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Presta\ImageBundle\Form\Type\ImageType;

use DRI\ForeingStudentBundle\Entity\Undergraduate;
use DRI\UsefulBundle\Form\DatePickerType;

class UndergraduateType extends AbstractType
{
    private $currentAction;


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->currentAction = $options['currentAction'];

        $builder
            ->add('type', ChoiceType::class, array(
                'label' => 'Género',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => Undergraduate::$UNDERGRADUATE_TYPE_CHOICE,
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
            ))

            ->add('ci', TextType::class, [
                'label' => 'CI',
                'attr' => [
                    'placeholder' => 'Agregue el CI del Estudiante',
                    'class' => 'ci-cu'
                ],
            ])
            ->add('names', TextType::class, [
                'label' => 'Nombre',
                'attr' => [
                    'placeholder' => 'Agregue el nombre del Estudiante',
                ],
            ])
            ->add('lastNames', TextType::class, [
                'label' => 'Apellidos',
                'attr' => [
                    'placeholder' => 'Agregue los apellidos del Estudiante',
                ],
            ])
            ->add('birthday', DatePickerType::class,[
                'label' => 'Fecha de Nacimiento',
                'attr' => [
                    'class'=>'date-picker first-date',
                    'size'=>'16',
                    'placeholder' => 'Seleccione la fecha de nacimiento',
                    'tabindex' => 2
                ],
            ])
            ->add('gender', ChoiceType::class, array(
                'label' => 'Género',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => array(
                    'Femenino'    =>'F',
                    'Masculino'   =>'M',
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
            ))
            ->add('country', EntityType::class, [
                'attr' => [ 'class' => 'country_list' ],
                'class' => 'DRIUsefulBundle:Country',
                'choice_label' => 'spName',
                'choice_value' => 'iso3',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('pictureFile', ImageType::class, [
                'cancel_button_class' => 'btn dark btn-outline',
                'save_button_class' => 'btn green',
                'enable_remote' => false,
                'required' => false,
                'max_width' => '250',
                'max_height' => '250',
                'preview_width' => '150',
                'preview_height' => '150',
            ])

            ->add('passportNumber', TextType::class, [
                'label' => 'No. Pasaporte',
                'attr' => [
                    'placeholder' => 'Agregue el número de pasaporte del Estudiante',
                ],
            ])
            ->add('email',EmailType::class, [
                'label' => 'Correo Institucional',
                'attr' => [
                    'placeholder' => 'Ingrese el email institucional'
                ]
            ])
            ->add('foreignEmail',EmailType::class, [
                'label' => 'Correo en otro servidor',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el email en el exterior'
                ]
            ])
            ->add('cellPhone',TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese un número de celular',
                    'class' => 'phone-require'
                ]
            ])

            ->add('entryDate', DatePickerType::class,[
                'label' => 'Fecha de entrada al centro',
                'attr' => [
                    'class'=>'date-picker first-date',
                    'size'=>'16',
                    'placeholder' => 'Seleccione la fecha de entrada al centro',
                    'tabindex' => 2
                ],
            ])
            ->add('expiryDate', DatePickerType::class,[
                'label' => 'Fecha de vencimiento de la estancia',
                'attr' => [
                    'class'=>'date-picker first-date',
                    'size'=>'16',
                    'placeholder' => 'Seleccione la fecha de vencimiento de la estancia',
                    'tabindex' => 2
                ],
            ])
            ->add('career', EntityType::class, array(
                'class' => 'DRIUsefulBundle:Career',
                'choice_label' => 'name',
                'placeholder' => 'Seleccione la Carrera',
                'attr' => array(
                    'class'=>'select2',
                ),
                'empty_data' => null,
                'required' => false

            ))
            ->add('year', ChoiceType::class, array(
                'label' => 'Año que cursa',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => Undergraduate::$UNDERGRADUATE_YEAR_CHOICE,
                'attr' => array(
                    'class'=>'radio-list',
                ),
                'choices_as_values' => true,
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'icheck', 'data-title' => $key];
                },
                'multiple'=> false,
                'expanded'=> false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Undergraduate::class,
            'currentAction' => null,
        ));

        $resolver->setRequired('currentAction');
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'foreingstudentbundle_undergraduate';
    }
}
