<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 20/10/2017
 * Time: 13:27
 */

namespace DRI\ClientBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use DRI\UsefulBundle\Form\DatePickerType;
use Presta\ImageBundle\Form\Type\ImageType;

use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

use DRI\UsefulBundle\Entity\School;

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
            //Datos Generales
           /* ->add('clientPictureFile', VichImageType::class, array(
                'label'         => 'Imagen',
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
                //'crop' => true,
            ))*/
            ->add('clientPictureFile', ImageType::class, [
                'enable_remote' => false,
                'max_width' => '150',
                'max_height' => '150',
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
                'choices'  => array(
                    'Docente'       =>'DOC',
                    'No Docente'    =>'NOD',
                    'Estudiante'    =>'EST',
                ),
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
                'choices'  => [
                    'CDR'   =>'cdr',
                    'CTC'   =>'ctc',
                    'FMC'   =>'fmc',
                    'UJC'   =>'ujc',
                    'PCC'   =>'pcc',
                ],
                'multiple'=> true,
            ])

            //Datos de pasaporte
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
                'choices'  => array(
                    'Soltero(a)'    =>'SOL',
                    'Casado(a)'     =>'CAS',
                    'Divorciado(a)' =>'DIV',
                    'Viudo(a)'      =>'VIU',
                ),
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
                'choices'  => array(
                    'Claros'    =>'Claros',
                    'Negros'    =>'Negros',
                    'Pardos'    =>'Pardos',
                ),
                'attr' => array(
                    'class'=>'bs-select',
                ),
                'multiple' => false,
            ))
            ->add('skinColor', ChoiceType::class, array(
                'placeholder' => 'Seleccione color de la piel',
                'label' => 'Color de la piel',
                'choices'  => array( //"Blanca", "Negra", "Amarilla", "Mulata", "Albina"
                    'Blanca'    =>'Blanca',
                    'Negra'     =>'Negra',
                    'Amarilla'  =>'Amarilla',
                    'Mulata'    =>'Mulata',
                    'Albina'    =>'Albina',
                ),
                'attr' => array(
                    'class'=>'bs-select',
                ),
                'multiple' => false,
            ))
            ->add('hairColor', ChoiceType::class, array(
                'placeholder' => 'Seleccione color del cabello',
                'label' => 'Color del cabello',
                'choices'  => array( //"Canoso", "Castaño", "Negro", "Rojo", "Rubio", "Otros"
                    'Canoso'    =>'Canoso',
                    'Castaño'   =>'Castaño',
                    'Negro'     =>'Negro',
                    'Rojo'      =>'Rojo',
                    'Rubio'     =>'Rubio',
                    'Otros'     =>'Otros',
                ),
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
                'class' => 'DRI\UsefulBundle\Entity\Country',
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
                'class' => 'DRI\UsefulBundle\Entity\Country',
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
            ->add('area', TextType::class, [
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

            //Datos en la UC
                //Estudiantes
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
            ->add('studentsCareer', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la carrera a la que pertenece'
                ],
            ])
            ->add('studentsSchool',EntityType::class, [
                'attr' => [ 'class' => 'select2' ],
                'class' => 'DRI\UsefulBundle\Entity\School',
                'choice_label' => 'name',
                'empty_data' => null,
                'required' => false
            ])
                //Trabajadores
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
                'choices'  => array(
                    'ATD'         => 'ATD',
                    'Adiestrado'  => 'ADI',
                    'Instructor'  => 'INS',
                    'Asistente'   => 'ASI',
                    'Auxiliar'    => 'AUX',
                    'Titular'     => 'TIT',
                ),
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ))
            ->add('workersSciGrade', ChoiceType::class, array(
                'placeholder' => 'Selecciones el grado científico',
                'label' => 'Grado Científico',
                'choices'  => array(
                    'Licenciado'  => 'LIC',
                    'Ingeniero'   => 'ING',
                    'Arquitecto'  => 'ARQ',
                    'Master'      => 'MSC',
                    'Doctor(a)'   => 'DRC',
                ),
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
            ->add('workersSchool', EntityType::class, [
                'attr' => [ 'class' => 'select2' ],
                'class' => 'DRI\UsefulBundle\Entity\School',
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