<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 20/10/2017
 * Time: 13:27
 */

namespace DRI\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Presta\ImageBundle\Form\Type\ImageType;

/**
 * Class ClientType
 *
 * @package DRI\ClientBundle\Form
 */
class ClientChangeImageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clientPictureFile', ImageType::class, [
                'cancel_button_class' => 'btn dark btn-outline',
                'save_button_class' => 'btn green',
                'enable_remote' => false,
                'max_width' => '250',
                'max_height' => '250',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DRI\ClientBundle\Entity\Client',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'clientbundle_client_change_image';
    }
}