<?php

namespace DRI\ExitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DRI\ExitBundle\Entity\Economic;

class EconomicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label'     => 'Tipo',
                'choices'   => array(
                    'Pasaje'    => 'Pasaje',
                    'Viáticos'  => 'Viáticos',
                    'Otros'     => 'Otros',
                ),
                'placeholder' => 'Elije una opción',
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Costo(CUC)'
            ])
            ->add('source', TextType::class, [
                'label' => 'Financiamiento'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Economic::class,
        ));
    }

    public function getBlockPrefix()
    {
        return 'EconomicType';
    }
}
