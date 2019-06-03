<?php

namespace DRI\ExitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DRI\ExitBundle\Entity\Economic;

class EconomicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label'     => 'Tipo',
                'choices'   => Economic::ECONOMIC_TYPE_CHOICE,
                'placeholder' => 'Elije una opción',
                'attr' => array(
                    'class'=>'bs-select',
                )
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Costo'
            ])
            ->add('currency', CurrencyType::class, [
                'label' => 'Moneda',
                'attr' => [
                    'class' => 'select2',
                ],
                'required' => false
            ])
            ->add('source', ChoiceType::class, [
                'label'     => 'Financiamiento',
                'choices'   => Economic::ECONOMIC_SOURCE_CHOICE,
                'placeholder' => 'Elije una opción',
                'attr' => array(
                    'class'=>'bs-select',
                )
            ])
            ->add('eventAcount', CheckboxType::class, array(
                'label' => false,
                'attr' => array(
                    'class'         => 'bootstrap-toggle',
                    'data-toggle'   => 'toggle',
                    'style'         => 'margin-left: 0px;',
                    'data-on'       => 'Si',
                    'data-off'      => 'No',
                    'data-onstyle'  => 'success',
                    'data-size'     => 'small'
                ),
                'required' => false,
            ))
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
        return 'economic_type';
    }
}
