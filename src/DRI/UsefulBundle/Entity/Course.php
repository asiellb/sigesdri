<?php

namespace DRI\UsefulBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Course
 *
 * @ORM\Table(name="usf_course")
 * @ORM\Entity(repositoryClass="DRI\UsefulBundle\Repository\CourseRepository")
 */
class Course
{

    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    COURSE'S CONSTANTS
     * *
     * ********************************************************************************
     **********************************************************************************/

    public static $COURSE_TYPE = [
        'DOC' => 'Doctorado',
        'MAE' => 'Maestría',
        'ESP' => 'Especialidad',
        'CCO' => 'Curso Corto',
    ];

    public static $COURSE_TYPE_CHOICE = [
        'Doctorado'    =>'DOC',
        'Maestría'     =>'MAE',
        'Especialidad' =>'ESP',
        'Curso Corto'  =>'CCO',
    ];



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    COURSE'S VARIABLES
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
     * @ORM\Column(type="string", length=3)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $coordinator;

    /**
     * @var Area
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Area", cascade={"persist"})
     * @ORM\JoinColumn(name="area_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $area;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    COURSE'S CONSTRUCTOR & TO_STRING METHOD
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    COURSE'S GET & SET METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/




    /**
     * Set name
     *
     * @param string $name
     *
     * @return Course
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set coordinator
     *
     * @param string $coordinator
     *
     * @return Course
     */
    public function setCoordinator($coordinator)
    {
        $this->coordinator = $coordinator;

        return $this;
    }

    /**
     * Get coordinator
     *
     * @return string
     */
    public function getCoordinator()
    {
        return $this->coordinator;
    }

    /**
     * Set area
     *
     * @param Area $area
     *
     * @return Course
     */
    public function setArea(Area $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return Area
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Course
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    COURSE'S ADITIONALS METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * @param $type
     * @return string
     */
    static function type_AcronimToName($type){
        switch ($type){
            case 'DOC': return 'Doctorado';break;
            case 'MAE': return 'Maestría';break;
            case 'ESP': return 'Especialidad';break;
            case 'CCO': return 'Curso Corto';break;
            default: return 'Tipo de Curso No Definido';break;
        }
    }

    /**
     * @param $type
     * @return string
     */
    static function type_NameToAcronim($type){
        switch ($type){
            case 'Doctorado': return 'DOC';break;
            case 'Maestría': return 'MAE';break;
            case 'Especialidad': return 'ESP';break;
            case 'Curso Corto': return 'CCO';break;
            default: return 'Tipo de Curso No Definido';break;
        }
    }
}
