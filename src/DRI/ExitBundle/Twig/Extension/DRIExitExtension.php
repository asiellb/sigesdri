<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 12/11/2016
 * Time: 23:21
 */

namespace DRI\ExitBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use DRI\ExitBundle\Entity\Application;
use DRI\ExitBundle\Entity\Mission;
use DRI\ExitBundle\Entity\Economic;
use DRI\ExitBundle\Entity\ManagerTravelPlan;


class DRIExitExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new TwigFunction('fn_exit_application_state', [$this, 'fnExitApplicationState']),
            new TwigFunction('fn_mission_concept', [$this, 'fnMissionConcept']),
            new TwigFunction('fn_economic_type', [$this, 'fnEconomicType']),
            new TwigFunction('fn_economic_source', [$this, 'fnEconomicSource']),
            new TwigFunction('fn_manager_travel_plan_state', [$this, 'fnManagerTravelPlanState']),
        );
    }

    public function fnExitApplicationState($state){
        return Application::state_AcronimToName($state);
    }

    public function fnMissionConcept($concept){
        return Mission::concept_AcronimToName($concept);
    }

    public function fnEconomicType($type){
        return Economic::type_AcronimToName($type);
    }

    public function fnEconomicSource($source){
        return Economic::source_AcronimToName($source);
    }

    public function fnManagerTravelPlanState($state){
        return ManagerTravelPlan::state_AcronimToName($state);
    }

    public function getName()
    {
        return 'dri_exit_extension';
    }
}