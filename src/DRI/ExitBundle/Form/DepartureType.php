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
use DRI\ExitBundle\Entity\ExitApplication;
use DRI\UsefulBundle\Form\DatePickerType;
use DRI\ClientBundle\Entity\Client;
use DRI\PassportBundle\Entity\Passport;

class DepartureType extends AbstractType
{
    private $em;

    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if($options['data']->hasClient()) {
            $applications = $options['applications'];
            $builder
                ->add('application', EntityType::class, [
                    'class' => 'DRIExitBundle:ExitApplication',
                    'choice_label' => 'number',
                    'required'      => true,
                    'choices' => $applications,
                    'empty_data' => null,
                    'attr' => [
                        'class' => 'select2',
                    ],
                ])
            ;
        }else{
            $builder
                ->add('application', EntityType::class, [
                    'class' => 'DRIExitBundle:ExitApplication',
                    'choice_label' => 'number',
                    'required'      => true,
                    'placeholder' => 'Seleccione la Solicitud ...',
                    'empty_data' => null,
                    'attr' => [
                        'class' => 'select2',
                    ],
                ])
            ;
        }

        $builder
            ->add('passportDelivery', DatePickerType::class)
            ->add('departureDate', DatePickerType::class)
            ->add('returnDate', DatePickerType::class, [
                'required'      => false,
            ])
            ->add('passportCollection', DatePickerType::class, [
                'required'      => false,
            ])
            ->add('observations', TextareaType::class, [
                'required'      => false,
            ])
            ->add('resultsFile', VichFileType::class, array(
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
            ))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

    protected function addElements(FormInterface $form, Client $client = null) {
        // 4. Add the passport element
        $form->add('client', EntityType::class, [
            'required'      => true,
            'data'          => $client,
            'placeholder'   => 'Seleccione el titular ...',
            'class'         => 'DRIClientBundle:Client',
            'choice_label'  => 'fullName',
            'empty_data'    => null,
            'attr'          => [
                'class' => 'select2',
            ],
        ]);

        // Passports empty, unless there is a selected Client (Edit View)
        $passports = array();

        // If there is a client stored in the Departure entity, load the passports of it
        if ($client) {
            // Fetch Passports of the Client if there's a selected client
            $repoPassport = $this->em->getRepository('DRIPassportBundle:Passport');

            $passports = $repoPassport->createQueryBuilder("q")
                ->where("q.holder = :clientid")
                ->setParameter("clientid", $client->getId())
                ->getQuery()
                ->getResult();
        }

        // Add the Passports field with the properly data
        $form
            ->add('passport', EntityType::class, [
                'required' => true,
                'placeholder' => 'Seleccione el pasaporte ...',
                'class' => 'DRIPassportBundle:Passport',
                'choices' => $passports,
                'attr' => [
                    'class' => 'select2',
                ],
            ])
        ;
    }

    function onPreSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();

        // Search for selected Client and convert it into an Entity
        $client = $this->em->getRepository('DRIClientBundle:Client')->find($data['client']);

        $this->addElements($form, $client);
    }

    function onPreSetData(FormEvent $event) {
        $departure = $event->getData();
        $form = $event->getForm();

        // When you create a new departure, the Client is always empty
        $client = $departure->getClient() ? $departure->getClient() : null;

        $this->addElements($form, $client);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DRI\ExitBundle\Entity\Departure',
            'cascade_validation' => true,
            'error_bubbling' => false,
            'applications' => null,
        ));

        $resolver->setRequired('applications');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dri_exitbundle_departure';
    }


}
