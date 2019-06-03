<?php

namespace DRI\UsefulBundle\Form;

use DRI\UsefulBundle\Entity\Area;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                'placeholder' => 'Seleccione el Tipo',
                'label' => 'Tipo',
                'choices'  => Area::AREA_TYPE_CHOICE,
                'attr' => array(
                    'class'=>'bs-select',
                ),
            ))
            ->add('name')
            ->add('leader')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DRI\UsefulBundle\Entity\Area'
        ));
    }
}
