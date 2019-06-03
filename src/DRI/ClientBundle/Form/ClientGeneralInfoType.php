<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 20/10/2017
 * Time: 13:27
 */

namespace DRI\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


use DRI\ClientBundle\Entity\Client;
use DRI\UsefulBundle\Form\DatePickerType;

/**
 * Class ClientType
 *
 * @package DRI\ClientBundle\Form
 */
class ClientGeneralInfoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //Datos Generales
            ->add('ci', TextType::class,[
                'label' => 'CI',
                'attr' => [
                    'placeholder' => 'Ingrese el CI',
                    'class' => 'ci-cu'
                ]
            ])
            ->add('firstName', TextType::class,[
                'label' => 'Primer Nombre',
                'attr' => [
                    'placeholder' => 'Ingrese el primer nombre'
                ]
            ])
            ->add('secondName', TextType::class, [
                'label' => 'Segundo Nombre',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el segundo nombre'
                ]

            ])
            ->add('firstLastName', TextType::class,[
                'label' => 'Primer Apellido',
                'attr' => [
                    'placeholder' => 'Ingrese el primer apellido'
                ]
            ])
            ->add('secondLastName', TextType::class,[
                'label' => 'Segundo Apellido',
                'attr' => [
                    'placeholder' => 'Ingrese el segundo apellido'
                ]
            ])
            ->add('birthday', DatePickerType::class,[
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Ingrese la fecha de nacimiento',
                    'readonly' => 'readonly'
                ]
            ])
            ->add('gender', ChoiceType::class, array(
                'label' => 'Género',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => Client::GENDER_CHOICE,
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
            ->add('email',TextType::class, [
                'label' => 'Correo Institucional',
                'attr' => [
                    'placeholder' => 'Ingrese el email institucional'
                ]
            ])
            ->add('foreignEmail',TextType::class,[
                'label' => 'Correo en otro servidor',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el email en el exterior'
                ]
            ])
            ->add('privatePhone',TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese un número de teléfono fijo',
                    'class' => 'phone-require'
                ]
            ])
            ->add('cellPhone',TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese un número de celular',
                    'class' => 'phone-require'
                ]
            ])
            ->add('clientType', ChoiceType::class, array(
                'label' => 'Tipo de Cliente',
                'label_attr' => [
                    'class' => 'radio-inline'
                ],
                'choices'  => Client::CLIENT_TYPES_CHOICE,
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
            ->add('languages', LanguageType::class, [
                'attr' => [
                    'class' => 'select2-multiple'
                ],
                'multiple' => true,
            ])
            ->add('organizations', ChoiceType::class, [
                'attr' => [
                    'class' => 'select2-multiple'
                ],
                'choices'  => Client::ORGANIZATION_CHOICE,
                'multiple'=> true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DRI\ClientBundle\Entity\Client',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'clientbundle_general_info';
    }
}