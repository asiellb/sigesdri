<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 20/10/2017
 * Time: 13:27
 */

namespace DRI\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;

use Presta\ImageBundle\Form\Type\ImageType;

use DRI\UsefulBundle\Form\DatePickerType;

use DRI\ClientBundle\Entity\Client;

/**
 * Class ClientType
 *
 * @package DRI\ClientBundle\Form
 */
class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*
             * DATOS GENERALES
             */
            ->add('clientPictureFile', ImageType::class, [
                'cancel_button_class' => 'btn dark btn-outline',
                'save_button_class' => 'btn green',
                'enable_remote' => false,
                'required' => false,
                'max_width' => '250',
                'max_height' => '250',
                'preview_width' => '150',
                'preview_height' => '150',
            ])
            ->add('ci', TextType::class,[
                'attr' => [
                    'placeholder' => 'Ingrese el CI',
                    'class' => 'ci-cu'
                ]
            ])
            ->add('firstName', TextType::class,[
                'attr' => [
                    'placeholder' => 'Ingrese el primer nombre',
                ]
            ])
            ->add('secondName', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el segundo nombre'
                ]

            ])
            ->add('firstLastName', TextType::class,[
                'attr' => [
                    'placeholder' => 'Ingrese el primer apellido'
                ]
            ])
            ->add('secondLastName', TextType::class,[
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
                'choices'  => Client::$GENDER_CHOICE,
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
                'attr' => [
                    'placeholder' => 'Ingrese el email institucional'
                ]
            ])
            ->add('foreignEmail',TextType::class,[
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
                'choices'  => Client::$CLIENT_TYPES_CHOICE,
                'attr' => array(
                    'class'=>'radio-list',
                ),
                'choices_as_values' => true,
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'icheck iradio', 'data-title' => $key];
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
                'choices'  => Client::$ORGANIZATION_CHOICE,
                'multiple'=> true,
            ])

            /*
             * DATOS DE PASAPORTE
             */
            ->add('mothersName',TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el nombre de la madre'
                ]
            ])
            ->add('fathersName',TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el nombre del padre'
                ]
            ])
            ->add('civilState', ChoiceType::class, array(
                'placeholder' => 'Seleccione estado civil',
                'label' => 'Estado civil',
                'choices'  => Client::$CIVIL_STATE_CHOICE,
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ))
            ->add('weight',NumberType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el peso'
                ]
            ])
            ->add('height',NumberType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la estatura'
                ]
            ])
            ->add('eyesColor', ChoiceType::class, array(
                'placeholder' => 'Seleccione color de los ojos',
                'label' => 'Color de los ojos',
                'choices'  => Client::$EYES_COLOR_CHOICE,
                'attr' => array(
                    'class'=>'bs-select',
                ),
                'multiple' => false,
            ))
            ->add('skinColor', ChoiceType::class, array(
                'placeholder' => 'Seleccione color de la piel',
                'label' => 'Color de la piel',
                'choices'  => Client::$SKIN_COLOR_CHOICE,
                'attr' => array(
                    'class'=>'bs-select',
                ),
                'multiple' => false,
            ))
            ->add('hairColor', ChoiceType::class, array(
                'placeholder' => 'Seleccione color del cabello',
                'label' => 'Color del cabello',
                'choices'  => Client::$HAIR_COLOR_CHOICE,
                'attr' => array(
                    'class'=>'bs-select',
                ),
                'multiple' => false,
            ))
            ->add('pvs',TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese las marcas personales visibles'
                ],
            ])
            ->add('citizenship',TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la ciudadania'
                ],
                'data' => 'cubana'
            ])
            ->add('countryBirth', EntityType::class, [
                'attr' => [ 'class' => 'country_list' ],
                'class' => 'DRIUsefulBundle:Country',
                'choice_label' => 'spName',
                'choice_value' => 'iso3',
                'empty_data' => null,
                'required' => false,
            ])
            ->add('stateBirth', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la provincia de nacimiento'
                ],
                'data' => 'Camagüey'
            ])
            ->add('cityBirth', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la ciudad de nacimiento',
                    'class' => 'city-exclude'
                ],
            ])
            ->add('foreignCityBirth', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la ciudad extrangera de nacimiento',
                    'class' => 'city-exclude'
                ],
            ])
            ->add('country', EntityType::class, [
                'attr' => [ 'class' => 'country_list' ],
                'class' => 'DRIUsefulBundle:Country',
                'choice_label' => 'spName',
                'choice_value' => 'iso3',
                'empty_data' => null,
                'required' => false
            ])
            ->add('state', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la provincia'
                ],
                'data' => 'Camagüey'
            ])
            ->add('city', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el municipio o la ciudad'
                ],
            ])
            ->add('district', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el reparto'
                ],
            ])
            ->add('street', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la calle',
                    'class' => 'street-exclude'
                ],
            ])
            ->add('highway', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la carretera',
                    'class' => 'street-exclude'
                ],
            ])
            ->add('firstBetween', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese primera entrecalle'
                ],
            ])
            ->add('secongBetween', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese segunda entrecalle'
                ],
            ])
            ->add('number', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el número',
                    'class' => 'number-exclude'
                ],
            ])
            ->add('km', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el Km',
                    'class' => 'number-exclude'
                ],
            ])
            ->add('building', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el edificio',
                    'class' => 'number-exclude'
                ],
            ])
            ->add('apartment', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el apartamento',
                    'data-dependent' => 'clientbundle_client_building',
                ],
            ])
            ->add('cpa', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la CPA'
                ],
            ])
            ->add('farm', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la Finca'
                ],
            ])
            ->add('town', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la localidad'
                ],
            ])
            ->add('district', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la circunscripción'
                ],
            ])
            ->add('postCode', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el código postal'
                ],
            ])

            /*
             * DATOS EN EL CENTRO
             */

            /*Estudiantes*/

            ->add('studentsYear', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el año que está cursando'
                ],
            ])
            ->add('studentsPosition', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el cargo'
                ],
            ])
            ->add('studentsCareer',EntityType::class, [
                'attr' => [ 'class' => 'select2' ],
                'class' => 'DRIUsefulBundle:Career',
                'choice_label' => 'name',
                'empty_data' => null,
                'required' => false
            ])
            ->add('studentsFaculty',EntityType::class, [
                'attr' => [ 'class' => 'select2' ],
                'class' => 'DRIUsefulBundle:Area',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where("a.type = :type")
                        ->setParameter("type", 'FAC')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
                'empty_data' => null,
                'required' => false
            ])

            /*Trabajadores*/

            ->add('workersOccupation', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la ocupación'
                ],
            ])
            ->add('workersSpecialty', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la especialidad'
                ],
            ])
            ->add('workersEduCategory', ChoiceType::class, array(
                'placeholder' => 'Selecciones la categoría docente',
                'label' => 'Categoría Docente',
                'choices'  => Client::$TEACHING_CATEGORY_CHOICE,
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ))
            ->add('workersSciGrade', ChoiceType::class, array(
                'placeholder' => 'Selecciones el grado científico',
                'label' => 'Grado Científico',
                'choices'  => Client::$SCIENTIFIC_GRADE_CHOICE,
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ))
            ->add('workersPosition', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el Cargo que ocupa'
                ],
            ])
            ->add('workersArea', EntityType::class, [
                'attr' => [ 'class' => 'select2' ],
                'class' => 'DRIUsefulBundle:Area',
                'choice_label' => 'name',
                'empty_data' => null,
                'required' => false
            ])
            ->add('workersFaculty', EntityType::class, [
                'attr' => [ 'class' => 'select2' ],
                'class' => 'DRIUsefulBundle:Area',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where("a.type = :type")
                        ->setParameter("type", 'FAC')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
                'empty_data' => null,
                'required' => false
            ])
            ->add('workersWorkPlace', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el lugar de trabajo'
                ],
            ])
            ->add('workersAdmissionDate', DatePickerType::class,[
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Fecha de entrada al centro'
                ],
                'required' => false,
            ])
            ->add('workersWorkPhone', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese teléfono del trabajo'
                ],
            ])
            ->add('workersPay', MoneyType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese salario'
                ],
                'currency' => 'CUP',
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
        return 'clientbundle_client';
    }
}