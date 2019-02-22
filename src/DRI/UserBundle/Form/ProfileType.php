<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DRI\UserBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

use Presta\ImageBundle\Form\Type\ImageType;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityRepository;

//use Liip\ImagineBundle\Form\Type\ImageType;

use DRI\UsefulBundle\Form\DatePickerType;

class ProfileType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class ,array(
            'label'=>'Nombre'
            ))
            ->add('lastName', TextType::class ,array(
                'label'=>'Apellidos'
            ))
            ->add('about', TextareaType::class ,array(
                'label'=>'Sobre mi'
            ))
            ->add('workPhone', TextType::class ,array(
                'label'=>'Teléfono'
            ))
            ->add('homePhone', TextType::class ,array(
                'label'=>'Teléfono particular'
            ))
            ->add('celPhone', TextType::class ,array(
                'label'=>'Celular'
            ))
            ->add('userImageFile', ImageType::class, [
                'label' => false,
                //'aspect_ratios' => [1],
                'cancel_button_class' => 'btn dark btn-outline',
                'save_button_class' => 'btn green',
                'enable_remote' => false,
                'max_width' => '150',
                'max_height' => '150',
                'required'      => false,
                'cropper_options' => [
                    //'movable' => false,
                    //'zoomable' => false,
                    //'rotatable' => false,
                    //'scalable' => false
                ]
            ])
            /*
            ->add('scienceCategory', TextType::class ,array(
                'label'=>'Categoría Científica'
            ))
            ->add('position', TextType::class ,array(
                'label'=>'Puesto'
            ))
            ->add('workDepartment', TextType::class ,array(
                'label'=>'Departamento'
            ))
            ->add('home', TextareaType::class ,array(
                'label'=>'Dirección paricular'
            ))
            */
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }
}
