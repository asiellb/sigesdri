<?php

namespace DRI\ExitBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Vich\UploaderBundle\Form\Type\VichFileType;


// 1. Include Required Namespaces
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityManagerInterface;

use DRI\ExitBundle\Entity\Economic;
use DRI\ExitBundle\Entity\Application;
use DRI\UsefulBundle\Form\DatePickerType;
use DRI\ClientBundle\Entity\Client;
use DRI\PassportBundle\Entity\Passport;

class DepartureType extends AbstractType
{
    private $currentAction;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->currentAction = $options['currentAction'];

        if(!$options['data']->hasClient()) {
            $builder
                ->add('client', EntityType::class, [
                    'required'      => true,
                    'placeholder'   => 'Seleccione el titular ...',
                    'class'         => 'DRIClientBundle:Client',
                    'choice_label'  => 'fullName',
                    'empty_data'    => null,
                    'attr'          => [
                        'class' => 'select2',
                    ],
                ]);
        }
        if(!$options['data']->hasApplication()) {
            $builder
                ->add('application', EntityType::class, [
                    'class' => 'DRIExitBundle:Application',
                    'choice_label' => 'number',
                    'required'      => true,
                    'placeholder' => 'Seleccione la Solicitud ...',
                    'empty_data' => null,
                    'attr' => [
                        'class' => 'select2',
                    ],
                ]);
        }
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
        if(!$options['data']->hasPassportDelivery()) {
            $builder
                ->add('passportDelivery', DatePickerType::class);
        }
        if(!$options['data']->hasDepartureDate()) {
            $builder
                ->add('departureDate', DatePickerType::class);
        }
        if ($this->currentAction == 'edit') {
            $builder
                ->add('returnDate', DatePickerType::class, [
                    'required' => false,
                ])
                ->add('passportCollection', DatePickerType::class, [
                    'required' => false,
                ])
                ->add('observations', TextareaType::class, [
                    'required' => false,
                ])
                ->add('resultsFile', VichFileType::class, array(
                    'required' => false,
                    'allow_delete' => true, // not mandatory, default is true
                    'download_link' => true, // not mandatory, default is true
                ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DRI\ExitBundle\Entity\Departure',
            'currentAction' => null,
        ));

        $resolver->setRequired('currentAction');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'exitbundle_departure';
    }


}
