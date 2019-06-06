<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 04/04/2017
 * Time: 3:51
 */

namespace DRI\ExitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Economic
 *
 * @ORM\Table(name="ext_economic")
 * @ORM\Entity(repositoryClass="DRI\ExitBundle\Repository\EconomicRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Vich\Uploadable
 */
class Economic
{

    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    ECONOMIC'S CONSTANTS
     * *
     * ********************************************************************************
     **********************************************************************************/

    const INITIAL_BALANCE_OF_EVENT_ACOUNT = 4000;

    public static $ECONOMIC_TYPE = [
        'P'  => 'Pasaje',
        'PA' => 'Pasaje Aéreo',
        'E'  => 'Estancia',
        'SM' => 'Seguro Médico',
        'V'  => 'Visa',
        'D'  => 'Dieta',
        'H'  => 'Hotel',
        'DB' => 'Dinero de Bolsillo',
        'I'  => 'Imprevisto',
        'IE' => 'Inscripción en Evento',
        'O'  => 'Otros',
    ];

    public static $ECONOMIC_TYPE_CHOICE = [
        'Pasaje'                => 'P',
        'Pasaje Aéreo'          => 'PA',
        'Estancia'              => 'E',
        'Seguro Médico'         => 'SM',
        'Visa'                  => 'V',
        'Dieta'                 => 'D',
        'Hotel'                 => 'H',
        'Dinero de Bolsillo'    => 'DB',
        'Imprevisto'            => 'I',
        'Inscripción en Evento' => 'IE',
        'Otros'                 => 'O',
    ];

    public static $ECONOMIC_SOURCE = [
        'PE'  => 'Parte Extranjera',
        'UC'  => 'Parte Cubana (UC)',
        'MES' => 'Parte Cubana (MES)',
    ];

    public static $ECONOMIC_SOURCE_CHOICE = [
        'Parte Extranjera'   => 'PE',
        'Parte Cubana (UC)'  => 'UC',
        'Parte Cubana (MES)' => 'MES',
    ];



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    ECONOMIC'S VARIABLES
     * *
     * ********************************************************************************
     **********************************************************************************/

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $currency;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $eventAcount;

    /**
     * Many economics have one exitApplication. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="DRI\ExitBundle\Entity\ManagerTravelPlan", inversedBy="financing")
     * @ORM\JoinColumn(name="exit_manager_travel_plan_id", referencedColumnName="id")
     */
    private $managerTravelPlan;

    /**
     * Many economics have one exitApplication. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="DRI\ExitBundle\Entity\Mission", inversedBy="economics")
     * @ORM\JoinColumn(name="mission_id", referencedColumnName="id")
     */
    private $mission;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    ECONOMIC'S GET & SET METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }



    /**
     * Economic constructor.
     *
     */
    public function __construct(){
        $this->eventAcount = false;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set managerTravelPlan
     *
     * @param ManagerTravelPlan $managerTravelPlan
     *
     * @return Economic
     */
    public function setManagerTravelPlan(ManagerTravelPlan $managerTravelPlan = null)
    {
        $this->managerTravelPlan = $managerTravelPlan;

        return $this;
    }

    /**
     * Get managerTravelPlan
     *
     * @return ManagerTravelPlan
     */
    public function getManagerTravelPlan()
    {
        return $this->managerTravelPlan;
    }

    /**
     * Set mission
     *
     * @param Mission $mission
     *
     * @return Economic
     */
    public function setMission(Mission $mission = null)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return Mission
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Economic
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set eventAcount
     *
     * @param boolean $eventAcount
     *
     * @return Economic
     */
    public function setEventAcount($eventAcount)
    {
        $this->eventAcount = $eventAcount;

        return $this;
    }

    /**
     * Get eventAcount
     *
     * @return boolean
     */
    public function getEventAcount()
    {
        return $this->eventAcount;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    ECONOMIC'S ADITIONALS METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * @param $type
     * @return string
     */
    static function type_AcronimToName($type){
        switch ($type){
            case 'P': return 'Pasaje';break;
            case 'PA': return 'Pasaje Aéreo';break;
            case 'E': return 'Estancia';break;
            case 'SM': return 'Seguro Médico';break;
            case 'V': return 'Visa';break;
            case 'D': return 'Dieta';break;
            case 'H': return 'Hotel';break;
            case 'DB': return 'Dinero de Bolsillo';break;
            case 'I': return 'Imprevisto';break;
            case 'IE': return 'Inscripción en Evento';break;
            case 'O': return 'Otros';break;
            default: return 'Tipo de Económico No Definido';break;
        }
    }

    /**
     * @param $type
     * @return string
     */
    static function type_NameToAcronim($type){
        switch ($type){
            case 'Pasaje': return 'P';break;
            case 'Pasaje Aéreo': return 'PA';break;
            case 'Estancia': return 'E';break;
            case 'Seguro Médico': return 'SM';break;
            case 'Visa': return 'V';break;
            case 'Dieta': return 'D';break;
            case 'Hotel': return 'H';break;
            case 'Dinero de Bolsillo': return 'DB';break;
            case 'Imprevisto': return 'I';break;
            case 'Inscripción en Evento': return 'IE';break;
            case 'Otros': return 'O';break;
            default: return 'Tipo de Económico No Definido';break;
        }
    }

    static function source_AcronimToName($source){
        switch ($source){
            case 'PE': return 'Parte Extranjera';break;
            case 'UC': return 'Parte Cubana (UC)';break;
            case 'MES': return 'Parte Cubana (MES)';break;
            default: return 'Tipo de Económico No Definido';break;
        }
    }

    static function source_NameToAcronim($source){
        switch ($source){
            case 'Parte Extranjera': return 'PE';break;
            case 'Parte Cubana (UC)': return 'UC';break;
            case 'Parte Cubana (MES)': return 'MES';break;
            default: return 'Tipo de Económico No Definido';break;
        }
    }

}
