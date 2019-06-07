<?php

namespace DRI\UsefulBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
