<?php

namespace DRI\ExitBundle\Form;

use DRI\ExitBundle\Entity\Economic;
use DRI\ExitBundle\Entity\ExitApplication;
use DRI\UsefulBundle\Form\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Vich\UploaderBundle\Form\Type\VichFileType;

use DRI\ClientBundle\Entity\Client;

class ExitApplicationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client', EntityType::class, array(
                'class' => 'DRI\ClientBundle\Entity\Client',
                'choice_label' => 'fullName',
                'choice_value' => function (Client $entity = null) {
                    return $entity ? $entity->getId() : '';
                },
                'placeholder' => 'Seleccione el titular ...',
                'empty_data' => null,
                'required' => false,
                'attr' => [
                    'class' => 'select2',
                ],
            ))
            ->add('country', EntityType::class, array(
                'attr' => [ 'class' => 'country_list' ],
                'class' => 'DRI\UsefulBundle\Entity\Country',
                'choice_label' => 'spName',
                'choice_value' => 'iso3',
                'empty_data' => null,
                'required' => false,

            ))
            ->add('institution', TextType::class, array(
                'attr' => [
                    'placeholder' => 'Nombre de la institución que visitará',
                ]
            ))
            ->add('lapsed', TextType::class, array(
                'attr' => [
                    'placeholder' => 'Tiempo de estancia en el exterior',
                ]
            ))
            ->add('exitDate', DatePickerType::class)
            ->add('arrivalDate', DatePickerType::class)
            ->add('concept', ChoiceType::class, array(
                'choices'   => array(
                    'Asistencia Técnica Exportada'  =>'ATE',
                    'Paquete de Postgrado'          =>'PPO',
                    'Asesoría'                      =>'ASE',
                    'Intercambio Académico'         =>'IAC',
                    'Evento'                        =>'EVE',
                    'Misión Oficial'                =>'MOF',
                    'Beca Predoctoral'              =>'BPR',
                    'Beca Postdoctoral'             =>'BPO',
                    'Proyecto Internacional'        =>'PIN',
                ),
                'placeholder' => 'Seleccione el Concepto de Salida',
                'attr' => [
                    'class' => 'select2',
                ],
            ))
            ->add('workPlanSynthesisFile', VichFileType::class, array(
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
            ))
            ->add('directiveSubstitute', TextType::class, array(
                'attr' => [
                    'placeholder' => 'Sustituto en caso de Cuadro',
                ],
                'required' => false,
            ))
            ->add('goeSubstitute', TextType::class, array(
                'attr' => [
                    'placeholder' => 'Sustituto en caso de GOE',
                ],
                'required' => false,
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
                    'attr' => [
                        'class' => 'item', // we want to use 'tr.item' as collection elements' selector
                    ],
                ],
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'required'      => false,
                'by_reference'  => true,
                'delete_empty' => true,
                'attr' => [
                    'class' => 'table economics',
                ],
            ))
            ->add('pccApproval', CheckboxType::class, array(
                'label' => 'Aprobada',
                'attr' => array(
                    'class' => 'icheck'
                ),
                'required' => false,
            ))
            ->add('pccApprovalDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('dcApproval', CheckboxType::class, array(
                'label' => 'Aprobada',
                'attr' => array(
                    'class' => 'icheck'
                ),
                'required' => false,
            ))
            ->add('dcApprovalDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('agreement')
            ->add('ivApproval', CheckboxType::class, array(
                'label' => 'Aprobada',
                'attr' => array(
                    'class' => 'icheck'
                ),
                'required' => false,
            ))
            ->add('ivApprovalDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('secretOffice', CheckboxType::class, array(
                'label' => 'Aprobada',
                'attr' => array(
                    'class' => 'icheck'
                ),
                'required' => false,
            ))
            ->add('secretOfficeDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('state', ChoiceType::class, array(
                'label' => 'Estado',
                'label_attr' => [
                    'class' => 'icheck-label'
                ],
                'choices'  => array(
                    'Confeccionada'  =>'CON',
                    'Aprobada'       =>'APR',
                    'Rechasada'      =>'REC',
                ),
                'data' => 'CON',
                'attr' => array(
                    'class'=>'input-group',
                ),
                'choices_as_values' => true,
                'choice_attr' => function($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'icheck'];
                },
                'multiple'=> false,
                'expanded'=> true,
            ))
            ->add('approvalDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('approvalObservations')
            ->add('rejectDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('rejectReason')
            ->add('proposedBy', EntityType::class, array(
                'class' => 'DRI\ClientBundle\Entity\Client',
                'choice_label' => 'fullName',
                'placeholder' => '¿Quién propone la salida?',
                'empty_data' => null,
                'required' => false,
                'attr' => ['class' => 'select2',],
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ExitApplication::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'DiscountCollectionType';
    }
}
