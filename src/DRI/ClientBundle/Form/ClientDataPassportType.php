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
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;


use DRI\UsefulBundle\Form\DatePickerType;

/**
 * Class ClientType
 *
 * @package DRI\ClientBundle\Form
 */
class ClientDataPassportType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

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
                    'data-dependent' => 'clientbundle_client_data_passport_building',
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
        return 'clientbundle_client_data_passport';
    }
}