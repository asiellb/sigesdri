<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 12/11/2016
 * Time: 23:21
 */

namespace DRI\ClientBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

use DRI\ClientBundle\Entity\Client;


class DRIClientExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('fn_client_type', [$this, 'fnClientType']),
            new \Twig_SimpleFunction('fn_gender', [$this, 'fnGender']),
            new \Twig_SimpleFunction('fn_civil_state', [$this, 'fnCivilState']),
            new \Twig_SimpleFunction('fn_teaching_category', [$this, 'fnTeachingCategory']),
            new \Twig_SimpleFunction('fn_scientific_grade', [$this, 'fnScientificGrade']),
        );
    }

    public function fnClientType($clientType){
        return Client::clientType_AcronimToName($clientType);
    }

    public function fnGender($gender){
        return Client::gender_AcronimToName($gender);
    }

    public function fnCivilState($civilState){
        return Client::civilState_AcronimToName($civilState);
    }

    public function fnTeachingCategory($teachingCategory){
        return Client::teachingCategory_AcronimToName($teachingCategory);
    }

    public function fnScientificGrade($scientificGrade){
        return Client::scientificGrade_AcronimToName($scientificGrade);
    }

    public function getName()
    {
        return 'dri_client_extension';
    }
}