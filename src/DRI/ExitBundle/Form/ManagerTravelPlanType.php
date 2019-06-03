<?php

namespace DRI\ExitBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Vich\UploaderBundle\Form\Type\VichFileType;

use DRI\ExitBundle\Entity\ManagerTravelPlan;
use DRI\ClientBundle\Entity\Client;
use DRI\UsefulBundle\Entity\Country;
use DRI\ExitBundle\Entity\Economic;
use DRI\UsefulBundle\Form\DatePickerType;

class ManagerTravelPlanType extends AbstractType
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
                ->add('client', EntityType::class, array(
                    'class' => 'DRIClientBundle:Client',
                    'choice_label' => 'fullName',
                    'choice_value' => function (Client $entity = null) {
                        return $entity ? $entity->getId() : '';
                    },
                    'placeholder' => 'Seleccione el cliente ...',
                    'empty_data' => null,
                    'required' => false,
                    'attr' => [
                        'class' => 'select2',
                    ],
                ));
        }

        $builder
            ->add('countries', EntityType::class, array(
                'class' => 'DRIUsefulBundle:Country',
                'choice_label' => 'spName',
                'choice_value' => 'iso3',
                'multiple' => true,
                'attr' => [
                    'class' => 'select2-multiple country_list'
                ],
            ))
            ->add('objetives', TextareaType::class,[
                'attr' => [
                    'placeholder' => 'Ingrese el objetivo del viaje ...'
                ]
            ])
            ->add('departureDate', DatePickerType::class,[
                'attr' => [
                    'class' => 'for-next-year',
                    'placeholder'=>'Selecione Fecha de Partida ...'
                ]
            ])
            ->add('lapsed', NumberType::class, array(
                'attr' => [
                    'placeholder' => 'Tiempo de estancia en el exterior',
                ]
            ))
            ->add('financing', CollectionType::class, array(
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
                'by_reference'  => false,
                'delete_empty'  => true,
                'attr' => [
                    'class' => 'table economics',
                ],
            ))
            ->add('state', ChoiceType::class, [
                'label' => 'Estado',
                'label_attr' => [
                    'class' => 'icheck-label'
                ],
                'choices'  => ManagerTravelPlan::MANAGER_TRAVEL_PLAN_STATE_CHOICE,
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
            ])
            ->add('approval', CheckboxType::class, array(
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
            ->add('approvalDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('approvalObservations')
            ->add('reject', CheckboxType::class, array(
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
            ->add('rejectDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('rejectReason')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ManagerTravelPlan::class,
            'currentAction' => null,
        ));

        $resolver->setRequired('currentAction');
    }

    public function getBlockPrefix()
    {
        return 'exitbundle_managertravelplan';
    }
}
