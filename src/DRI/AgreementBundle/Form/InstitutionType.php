<?php

namespace DRI\AgreementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Doctrine\ORM\EntityRepository;

use DRI\UsefulBundle\Form\DatePickerType;
use Presta\ImageBundle\Form\Type\ImageType;

use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

use DRI\AgreementBundle\Entity\Institution;

class InstitutionType extends AbstractType
{
    private $currentAction;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->currentAction = $options['currentAction'];

        $builder
            ->add('name', TextType::class,[
                'attr' => [
                    'placeholder' => 'Ingrese el Nombre de la Institución',
                ]
            ])
            ->add('acronym', TextType::class,[
                'attr' => [
                    'placeholder' => 'Ingrese las Siglas de la Institución',
                ]
            ])
            ->add('country', EntityType::class, array(
                'attr' => [ 'class' => 'country_list' ],
                'class' => 'DRIUsefulBundle:Country',
                'choice_label' => 'spName',
                'choice_value' => 'iso3',
                'empty_data' => null,
                'required' => false,

            ))
            ->add('countryState', TextType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el Estado',
                ]
            ])
            ->add('province', TextType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la Provincia',
                ]
            ])
            ->add('city', TextType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese la Ciudad',
                ]
            ])
            ->add('logoFile', ImageType::class, [
                'cancel_button_class' => 'btn dark btn-outline',
                'save_button_class' => 'btn green',
                'enable_remote' => false,
                'required' => false,
                'max_width' => '200',
                'max_height' => '200',
                'preview_width' => '150',
                'preview_height' => '150',
            ])
            ->add('url', UrlType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ingrese el URL',
                ]
            ])
            ->add('rector', TextType::class,[
                'attr' => [
                    'placeholder' => 'Ingrese el Nombre del Rector',
                ],
                'required' => false,
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Institution::class,
            'currentAction' => null,
        ));

        $resolver->setRequired('currentAction');
    }

    public function getBlockPrefix()
    {
        return 'foreing_institution';
    }
}
