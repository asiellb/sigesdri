<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 20/01/2017
 * Time: 18:18
 */

namespace DRI\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ResettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName')
                ->add('lastName')
                ->add('userImageFile', VichImageType::class, array(
                    'label'         => 'Foto',
                    'required'      => false,
                    'allow_delete'  => true, // not mandatory, default is true
                    'download_link' => true, // not mandatory, default is true
                ))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ResettingFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_resetting';
    }

    public function getBlockPrefix()
    {
        return 'app_user_resetting';
    }

    // BC for SF < 3.0
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
