<?php

namespace DRI\UsefulBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Validator\Constraints\Date;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SelectTipoBecasType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'choices' => array(
                    'Investigación'=>'Investigación',
                    'Curso de Especializados'=>'Curso de Especializados',
                    'Maestría'=> 'Maestría',
                    'Doctorado'=>'Doctorado',
                    'Posdoctorado'=>'Posdoctorado',
                    'Otro'=>'Otro'
                ),
                'attr' => array(
                    'class'=>'selectpicker',
                    'title'=>'Selecione un Tipo ...'
                ),
                'required'   => false,
                'empty_data' => 'Mixta'
            )
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
