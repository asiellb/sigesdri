<?php

namespace DRI\TramitesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

use DRI\UtilBundle\Form\DatePickerType;


class PasaporteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('noPas', TextType::class, array(
                'label' => false,
                'attr' => [
                    'placeholder' => 'Agregue el número de pasaporte ...'
                ]
            ))
            ->add('titular', EntityType::class, array(
                'class' => 'DRI\ClienteBundle\Entity\Persona',
                'choice_label' => 'nombreCompleto',
                'empty_data' => null,
                'attr' => array(
                    'class'=>'selectpicker',
                    'title'=>'Selecione el titular ...'
                ),
                'empty_data' => 'Mixta'

            ))
            ->add('fechaExp', DatePickerType::class, array(
                'label' => 'Expedición',
            ))
            ->add('fechaVen', DatePickerType::class, array(
                'label' => 'Vencimiento',
            ))
            ->add('tipoPas', ChoiceType::class, array(
                'label' => 'Tipo',
                'label_attr' => [
                    'class' => 'icheck-label'
                ],
                'choices'  => array(
                    'Oficial' => 'OFI',
                    'Ordinario' => 'ORD',
                ),
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
            ->add('primeraPaginaFile', VichImageType::class, array(
                'label'         => 'Imagen',
                'required'      => false,
                'allow_delete'  => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
            ))
            ->add('estadoPas', ChoiceType::class, array(
                'label' => 'Estado',
                'label_attr' => [
                    'class' => 'icheck-label'
                ],
                'choices'  => array(
                    'Activo' => 'ACT',
                    'Vencido' => 'VEN',
                    'Baja' => 'BAJ',
                ),
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
            ->add('causaBaja', TextareaType::class, array(
                'label' => 'Causa de Baja',
                'attr' => [
                    'placeholder' => 'Argumente la(s) causa(s) de la baja ...',
                ]
            ))
            ->add('fechaBaja', DatePickerType::class, array(
                'label' => 'Fecha de Baja',
                'required' => false
            ))

        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DRI\TramitesBundle\Entity\Pasaporte'
        ));
    }
}
