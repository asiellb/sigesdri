<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 12/11/2016
 * Time: 23:21
 */

namespace DRI\AgreementBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;

use Twig\TwigFunction;

use DRI\AgreementBundle\Entity\Institutional;


class DRIAgreementExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new TwigFunction('fn_institutional_action_type', [$this, 'fnInstitutionalActionType']),
            
        );
    }

    public function fnInstitutionalActionType($actionType){
        return Institutional::actionType_AcronimToName($actionType);
    }

    public function getName()
    {
        return 'dri_agreement_extension';
    }
}