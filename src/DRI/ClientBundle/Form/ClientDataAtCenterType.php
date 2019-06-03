<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 20/10/2017
 * Time: 13:27
 */

namespace DRI\ClientBundle\Form;

use Doctrine\ORM\Mapping\Entity;
use DRI\ClientBundle\Entity\Client;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;


use DRI\UsefulBundle\Form\DatePickerType;

/**
 * Class ClientType
 *
 * @package DRI\ClientBundle\Form
 */
class ClientDataAtCenterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('studentsCareer',EntityType::class, [
                'attr' => [ 'class' => 'select2' ],
                'class' => 'DRIUsefulBundle:Career',
                'choice_label' => 'name',
                'empty_data' => null,
                'required' => false
            ])
            ->add('studentsFaculty',EntityType::class, [
                'class' => 'DRIUsefulBundle:Area',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where("a.type = :type")
                        ->setParameter("type", 'FAC')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'Seleccione la Facultad',
                'attr' => array(
                    'class'=>'select2',
                ),
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
                'choices'  => Client::TEACHING_CATEGORY_CHOICE,
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ))
            ->add('workersSciGrade', ChoiceType::class, array(
                'placeholder' => 'Selecciones el grado científico',
                'label' => 'Grado Científico',
                'choices'  => Client::SCIENTIFIC_GRADE_CHOICE,
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
            ->add('workersWorkPlace', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el lugar de trabajo'
                ],
            ])
            ->add('workersArea', EntityType::class, [
                'class' => 'DRIUsefulBundle:Area',
                'choice_label' => 'name',
                'placeholder' => 'Seleccione el Área',
                'attr' => array(
                    'class'=>'select2',
                ),
                'empty_data' => null,
                'required' => false
            ])
             ->add('workersFaculty', EntityType::class, [
                'class' => 'DRIUsefulBundle:Area',
                 'query_builder' => function (EntityRepository $er) {
                     return $er->createQueryBuilder('a')
                         ->where("a.type = :type")
                         ->setParameter("type", 'FAC')
                         ->orderBy('a.name', 'ASC');
                 },
                 'choice_label' => 'name',
                'placeholder' => 'Seleccione la Facultad',
                'attr' => array(
                    'class'=>'select2',
                ),
                'empty_data' => null,
                'required' => false
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
        return 'clientbundle_client_data_at_center';
    }
}