<?php

namespace DRI\ExitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

use DRI\ExitBundle\Entity\CommandFile;
use DRI\ExitBundle\Entity\Mission;
use DRI\ExitBundle\Entity\Economic;
use DRI\ExitBundle\Entity\Application;
use DRI\UsefulBundle\Form\DatePickerType;

class CommandFileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ipwActions', CKEditorType::class, array(
                'config_name' => 'my_config'
            ))
            ->add('mwoActions', CKEditorType::class, array(
                'attr' => [
                    'placeholder' => 'Acciones para el cumplimiento de los objetivos de trabajo de la misión encomendada',
                    //'class' => 'ckeditor'
                ]
            ))
            ->add('ittActions', CKEditorType::class, array(
                'attr' => [
                    'placeholder' => 'Acciones para identificar las tendencias de la educación superior y el desarrollo de las nuevas tecnologías',
                    //'class' => 'ckeditor'
                ]
            ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CommandFile::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'commandfile_type';
    }


}
