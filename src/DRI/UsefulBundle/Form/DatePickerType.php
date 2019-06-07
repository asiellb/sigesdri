<?php

namespace DRI\UsefulBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;

class DatePickerType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('widget' => 'single_text'
            ,'format' => 'dd/MM/yyyy'
            ,'attr' => array('class'=>'date-picker'
                ,'size'=>'16'
                ,'placeholder'=>'Selecione una Fecha ...'
                )
            )
        );
    }

    public function getParent()
    {
        return DateType::class;
    }
}
