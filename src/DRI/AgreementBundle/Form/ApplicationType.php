<?php

namespace DRI\AgreementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Doctrine\ORM\EntityRepository;

use DRI\UsefulBundle\Form\DatePickerType;
use Presta\ImageBundle\Form\Type\ImageType;

use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

use DRI\AgreementBundle\Entity\Application;

class ApplicationType extends AbstractType
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
            ->add('institution', EntityType::class, array(
                'class' => 'DRIAgreementBundle:Institution',
                'choice_label' => 'name',
                'required'      => true,
                'placeholder' => 'Seleccione la Institución Extranjera',
                'empty_data' => null,
                'attr' => [
                    'class' => 'select2',
                ],
            ))
            ->add('background',TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Ingrese los antecedentes de cooperación'
                ],
            ])
            ->add('objetives',TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Ingrese los objetivos'
                ],
            ])
            ->add('basement',TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Ingrese la fundamentación'
                ],
            ])
            ->add('commitments',TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Ingrese los compromisos contraidos'
                ],
            ])
            ->add('validity', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ingrese la vigencia que tendrá el convenio'
                ],
            ])
            ->add('memberForCubanPart', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ingrese el nombre del integrante por la parte cubana'
                ],
            ])
            ->add('memberForForeignPart', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ingrese el nombre del integrante por la parte extranjera'
                ],
            ])
            ->add('results', TextType::class, [
                'attr' => [
                    'placeholder' => 'Ingrese los resultados que se esperan obtener'
                ],
            ])
            ->add('expenses', MoneyType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese los gastos por salidas al exterior'
                ],
                'currency' => 'CUC',
            ])
            ->add('state', ChoiceType::class, array(
                'label' => 'Estado',
                'label_attr' => [
                    'class' => 'icheck-label'
                ],
                'choices'  => Application::AGREEMENT_APPLICATION_STATE_CHOICE,
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
            ->add('confirmDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('rejectDate', DatePickerType::class, array(
                'required' => false,
            ))
            ->add('rejectReasons',TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese las Razones del Rechazo'
                ],
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Application::class,
            'currentAction' => null,
        ));

        $resolver->setRequired('currentAction');
    }

    public function getBlockPrefix()
    {
        return 'agreement_application';
    }
}
