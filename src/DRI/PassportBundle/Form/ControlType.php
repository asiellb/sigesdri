<?php

namespace DRI\PassportBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use DRI\UsefulBundle\Form\DatePickerType;

class ControlType extends AbstractType
{
    private $currentAction;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->currentAction = $options['currentAction'];

        if(!$options['data']->hasPassport()) {
            $builder
                ->add('passport', EntityType::class, [
                    'required' => true,
                    'placeholder' => 'Seleccione el pasaporte ...',
                    'class' => 'DRIPassportBundle:Passport',
                    'choice_label' => 'number',
                    'attr' => [
                        'class' => 'select2',
                    ],
                ]);
        }

        $builder
            ->add('deliveryDate', DatePickerType::class,[
                'label' => 'Fecha de Entrega',
                'required' => true,
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Fecha de Entrega'
                ],
            ])
            ->add('receivesSpecialist', EntityType::class, [
                'required' => true,
                'placeholder' => 'Seleccione el usuario que recoge ...',
                'class' => 'DRIUserBundle:User',
                'choice_label' => 'username',
                'attr' => [
                    'class' => 'select2',
                ],
            ])
        ;

        if ($this->currentAction == 'edit'){
            $builder
                ->add('pickUpDate', DatePickerType::class,[
                    'label' => 'Fecha de Recogida',
                    'required' => false,
                    'attr' => [
                        'class'=>'date-picker',
                        'size'=>'16',
                        'placeholder' => 'Fecha de Recogida'
                    ],
                ])
                ->add('deliversSpecialist', EntityType::class, [
                    'required' => false,
                    'placeholder' => 'Seleccione el usuario que entrega ...',
                    'class' => 'DRIUserBundle:User',
                    'choice_label' => 'username',
                    'attr' => [
                        'class' => 'select2',
                    ],
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
            'data_class' => 'DRI\PassportBundle\Entity\Control',
            'currentAction' => null,
        ));

        $resolver->setRequired('currentAction');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'passportbundle_control';
    }
}
