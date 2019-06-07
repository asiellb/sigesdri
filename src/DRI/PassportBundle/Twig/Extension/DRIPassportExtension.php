<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 12/11/2016
 * Time: 23:21
 */

namespace DRI\PassportBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use DRI\PassportBundle\Entity\Passport;
use DRI\PassportBundle\Entity\Application;


class DRIPassportExtension extends AbstractExtension
{
    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('fn_passport_type', [$this, 'fnPassportType']),
            new TwigFunction('fn_passport_state', [$this, 'fnPassportState']),
            new TwigFunction('fn_passport_application_reason', [$this, 'fnPassportApplicationReason']),
            new TwigFunction('fn_passport_application_type', [$this, 'fnPassportApplicationType']),
            new TwigFunction('fn_passport_application_state', [$this, 'fnPassportApplicationState']),
        );
    }

    /**
     * @param $type
     * @return string
     */
    public function fnPassportType($type){
        return Passport::type_AcronimToName($type);
    }

    /**
     * @param $state
     * @return string
     */
    public function fnPassportState($state){
        return Passport::state_AcronimToName($state);
    }

    /**
     * @param $reason
     * @return string
     */
    public function fnPassportApplicationReason($reason){
        return Application::reason_AcronimToName($reason);
    }

    /**
     * @param $type
     * @return string
     */
    public function fnPassportApplicationType($type){
        return Application::type_AcronimToName($type);
    }

    /**
     * @param $state
     * @return string
     */
    public function fnPassportApplicationState($state){
        return Application::state_AcronimToName($state);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dri_passport_extension';
    }
}