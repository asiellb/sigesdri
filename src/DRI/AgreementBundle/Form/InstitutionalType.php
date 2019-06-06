<?php

namespace DRI\AgreementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;

use Vich\UploaderBundle\Form\Type\VichFileType;

use DRI\AgreementBundle\Entity\Institutional;
use DRI\UsefulBundle\Form\DatePickerType;

class InstitutionalType extends AbstractType
{
    private $currentAction;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->currentAction = $options['currentAction'];

        if(!$options['data']->hasApplication()) {
            $builder
                ->add('application', EntityType::class, [
                    'class' => 'DRIAgreementBundle:Application',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                            ->where("a.state = :state")
                            ->andWhere("a.used = :used")
                            ->setParameter("state", "APR")
                            ->setParameter("used", false);
                    },
                    'choice_label' => 'number',
                    'required'      => true,
                    'placeholder' => 'Seleccione el Número de Ficha',
                    'empty_data' => null,
                    'attr' => [
                        'class' => 'select2',
                    ],
                ]);
        }
        if(!$options['data']->hasInstitution()) {
            $builder
                ->add('institution',EntityType::class, [
                    'class' => 'DRIAgreementBundle:Institution',
                    'choice_label' => 'name',
                    'placeholder' => 'Seleccione la Institución Extranjera',
                    'required' => true,
                    'empty_data' => null,
                    'attr' => array(
                        'class'=>'select2',
                    ),
                ]);
        }

        $builder
            ->add('actionType', ChoiceType::class, array(
                'label' => 'Tipo de acción',
                'label_attr' => [
                    'class' => 'icheck-label'
                ],
                'choices'  => Institutional::$INSTITUTIONAL_ACTION_TYPE_CHOICE,
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
            ->add('parent',EntityType::class, [
                'class' => 'DRIAgreementBundle:Institutional',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where("p.parent is NULL");
                },
                'choice_label' => 'number',
                'placeholder' => 'Seleccione el Convenio Anterior',
                'required' => true,
                'empty_data' => null,
                'attr' => array(
                    'class'=>'select2',
                ),
            ])
            ->add('number', TextType::class,[
                'attr' => [
                    'placeholder' => 'Ingrese el Número de Convenio',
                ]
            ])
            ->add('startDate', DatePickerType::class,[
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Ingrese la fecha de inicio',
                ]
            ])
            ->add('endDate', DatePickerType::class,[
                'attr' => [
                    'class'=>'date-picker',
                    'size'=>'16',
                    'placeholder' => 'Ingrese la fecha de final',
                ]
            ])
            ->add('mesApproval', CheckboxType::class, array(
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
            ->add('digitalCopyFile', VichFileType::class, array(
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
            ))
            ->add('benefitedAreas', EntityType::class, array(
                'class' => 'DRIUsefulBundle:Area',
                'choice_label' => 'name',

                //'expanded' => true,
                'multiple' => true,

                'attr' => [
                    'class' => 'select2-multiple'
                ],
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DRI\AgreementBundle\Entity\Institutional',
            'currentAction' => null,
        ));

        $resolver->setRequired('currentAction');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'agreementbundle_institutional';
    }
}
