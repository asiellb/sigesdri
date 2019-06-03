<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 12/11/2016
 * Time: 23:21
 */

namespace DRI\PassportBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

use DRI\PassportBundle\Entity\Passport;
use DRI\PassportBundle\Entity\Application;


class DRIPassportExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('fn_passport_type', [$this, 'fnPassportType']),
            new \Twig_SimpleFunction('fn_passport_state', [$this, 'fnPassportState']),
            new \Twig_SimpleFunction('fn_passport_application_reason', [$this, 'fnPassportApplicationReason']),
            new \Twig_SimpleFunction('fn_passport_application_type', [$this, 'fnPassportApplicationType']),
            new \Twig_SimpleFunction('fn_passport_application_state', [$this, 'fnPassportApplicationState']),
        );
    }

    public function fnPassportType($type){
        return Passport::type_AcronimToName($type);
    }

    public function fnPassportState($state){
        return Passport::state_AcronimToName($state);
    }

    public function fnPassportApplicationReason($reason){
        return Application::reason_AcronimToName($reason);
    }

    public function fnPassportApplicationType($type){
        return Application::type_AcronimToName($type);
    }

    public function fnPassportApplicationState($state){
        return Application::state_AcronimToName($state);
    }

    public function getName()
    {
        return 'dri_passport_extension';
    }
}