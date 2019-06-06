<?php

namespace DRI\ExitBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use FOS\CKEditorBundle\Form\Type\CKEditorType;

use DRI\ExitBundle\Entity\CommandFile;

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
