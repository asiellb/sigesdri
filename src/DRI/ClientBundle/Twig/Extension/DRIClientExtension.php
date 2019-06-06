<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 12/11/2016
 * Time: 23:21
 */

namespace DRI\ClientBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use DRI\ClientBundle\Entity\Client;


class DRIClientExtension extends AbstractExtension
{
    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('fn_client_type', [$this, 'fnClientType']),
            new TwigFunction('fn_gender', [$this, 'fnGender']),
            new TwigFunction('fn_civil_state', [$this, 'fnCivilState']),
            new TwigFunction('fn_teaching_category', [$this, 'fnTeachingCategory']),
            new TwigFunction('fn_scientific_grade', [$this, 'fnScientificGrade']),
        );
    }

    /**
     * @param $clientType
     * @return string
     */
    public function fnClientType($clientType){
        return Client::clientType_AcronimToName($clientType);
    }

    /**
     * @param $gender
     * @return string
     */
    public function fnGender($gender){
        return Client::gender_AcronimToName($gender);
    }

    /**
     * @param $civilState
     * @return string
     */
    public function fnCivilState($civilState){
        return Client::civilState_AcronimToName($civilState);
    }

    /**
     * @param $teachingCategory
     * @return string
     */
    public function fnTeachingCategory($teachingCategory){
        return Client::teachingCategory_AcronimToName($teachingCategory);
    }

    /**
     * @param $scientificGrade
     * @return string
     */
    public function fnScientificGrade($scientificGrade){
        return Client::scientificGrade_AcronimToName($scientificGrade);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dri_client_extension';
    }
}