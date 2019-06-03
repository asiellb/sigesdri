<?php

namespace DRI\ExitBundle\Form;

use function PHPSTORM_META\type;
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
use Doctrine\ORM\EntityRepository;
use Vich\UploaderBundle\Form\Type\VichFileType;

use DRI\ClientBundle\Entity\Client;
use DRI\ClientBundle\Repository\ClientRepository;
use DRI\ExitBundle\Entity\Application;
use DRI\ExitBundle\Entity\Economic;
use DRI\ExitBundle\Entity\Mission;
use DRI\ExitBundle\Entity\CommandFile;
use DRI\ExitBundle\Form\MissionType;
use DRI\ExitBundle\Form\CommandFileType;
use DRI\UsefulBundle\Form\DatePickerType;

class ApplicationType extends AbstractType
{
    private $currentAction;

    private $type;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->currentAction = $options['currentAction'];

        $this->type = $options['type'];

        if(!$options['data']->hasClient()) {
            $builder
                ->add('client', EntityType::class, array(
                    'class' => 'DRIClientBundle:Client',
                    'choice_label' => function(Client $client){
                        return $client->getCi().' - '.$client->getFullName();
                    },
                    'choice_value' => function (Client $entity = null) {
                        return $entity ? $entity->getId() : '';
                    },
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->where("c.clientType = :clientType")
                            ->setParameter("clientType", $this->type)
                            ->orderBy('c.fullName', 'ASC');
                    },
                    'placeholder' => 'Seleccione el titular ...',
                    'empty_data' => null,
                    'required' => false,
                    'attr' => [
                        'class' => 'select2',
                    ],
                ));
        }

        $builder
            ->add('proposedBy', EntityType::class, array(
                'class' => 'DRIClientBundle:Client',
                'choice_label' => 'fullName',
                'placeholder' => '¿Quién propone la salida?',
                'empty_data' => null,
                'required' => false,
                'attr' => ['class' => 'select2',],
            ))

            ->add('exitDate', DatePickerType::class)
            ->add('arrivalDate', DatePickerType::class)
            ->add('lapsed', TextType::class, array(
                'attr' => [
                    'placeholder' => 'Tiempo de estancia en el exterior',
                    'readonly' => 'readonly'
                ]
            ))

            ->add('missions', CollectionType::class, array(
                'label' => false,
                'entry_type' => MissionType::class,
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => '', // we want to use 'tr.item' as collection elements' selector
                    ],
                ],
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'required'      => false,
                'by_reference'  => false,
                'delete_empty'  => true,
                'attr' => [
                    'class' => 'row collection-missions',
                ],
            ))

            ->add('digitalCopyFile', VichFileType::class, array(
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
            ))

            ->add('pccApproval', CheckboxType::class, array(
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
            ->add('pccApprovalDate', DatePickerType::class, array(
                'required' => false,
            ))

            ->add('vriApproval', CheckboxType::class, array(
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
            ->add('vriApprovalDate', DatePickerType::class, array(
                'required' => false,
            ))

            ->add('osApproval', CheckboxType::class, array(
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
            ->add('osApprovalDate', DatePickerType::class, array(
                'required' => false,
            ))

            ->add('rsApproval', CheckboxType::class, array(
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
            ->add('rsApprovalDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('agreement')

            ->add('state', ChoiceType::class, [
                'label' => 'Estado',
                'label_attr' => [
                    'class' => 'icheck-label'
                ],
                'choices'  => Application::EXIT_APPLICATION_STATE_CHOICE,
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
            ->add('approvalDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('approvalObservations')
            ->add('rejectDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('rejectReason')
        ;

        if ($this->type != 'est'){
            $builder
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
                ));
        }

        if ($this->type == 'dir'){
            $builder
                ->add('commandFile', CommandFileType::class, [
                    'label' => false
                ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Application::class,
            'currentAction' => null,
            'type' => null,
        ));

        $resolver->setRequired('currentAction');
        $resolver->setRequired('type');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'exitbundle_application';
    }


}
