<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 20/10/2017
 * Time: 13:27
 */

namespace DRI\UserBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

//use Liip\ImagineBundle\Form\Type\ImageType;

use DRI\UsefulBundle\Form\DatePickerType;
use Presta\ImageBundle\Form\Type\ImageType;

/**
 * Class UserType
 *
 * @package DRI\UserBundle\Form
 */
class ProfileChangeImageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DRI\UserBundle\Entity\User',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_user_change_image';
    }
}