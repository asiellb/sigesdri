<?php

namespace DRI\UsefulBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DRI\UsefulBundle\Useful\Useful;

use DRI\ClientBundle\Entity\Client;
use DRI\AgreementBundle\Entity\Institutional;
use DRI\UsefulBundle\Entity\Career;

/**
 * Facultad
 *
 * @ORM\Table(name="usf_area")
 * @ORM\Entity(repositoryClass="DRI\UsefulBundle\Repository\AreaRepository")
 * @UniqueEntity("name")
 * @Vich\Uploadable
 */
class Area
{
    const AREA_TYPE = [
        'Rectoría' => 'REC',
        'Vicerectoría' => 'VRE',
        'Dirección' => 'DIR',
        'Facultad' => 'FAC',
        'Departamento' => 'DEP',
    ];

    const AREA_TYPE_CHOICE = [
        'REC' => 'Rectoría',
        'VRE' => 'Vicerectoría',
        'DIR' => 'Dirección',
        'FAC' => 'Facultad',
        'DEP' => 'Departamento',
    ];

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
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=1,max=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(min=0,max=100)
     */
    private $leader;

    /**
     * @ORM\OneToMany(targetEntity="DRI\UsefulBundle\Entity\Career", mappedBy="area")
     */
    private $carrers;

    /**
     * @ORM\OneToMany(targetEntity="DRI\ClientBundle\Entity\Client", mappedBy="studentsFaculty")
     */
    private $students;

    /**
     * @ORM\OneToMany(targetEntity="DRI\ClientBundle\Entity\Client", mappedBy="workersFaculty")
     */
    private $profesors;

    /**
     * @ORM\OneToMany(targetEntity="DRI\ClientBundle\Entity\Client", mappedBy="workersArea")
     */
    private $workers;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->carrers = new ArrayCollection();
        $this->students = new ArrayCollection();
        $this->profesors = new ArrayCollection();
        $this->workers = new ArrayCollection();
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
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Area
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
     * Add career
     *
     * @param Career $career
     *
     * @return Area
     */
    public function addCareer(Career $career)
    {
        $this->carrers[] = $career;

        return $this;
    }

    /**
     * Remove career
     *
     * @param Career $career
     */
    public function removeCareer(Career $career)
    {
        $this->carrers->removeElement($career);
    }

    /**
     * Get students
     */
    public function getCareers()
    {
        return $this->carrers;
    }

    /**
     * Add student
     *
     * @param Client $student
     *
     * @return Area
     */
    public function addStudent(Client $student)
    {
        $this->students[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param Client $student
     */
    public function removeStudent(Client $student)
    {
        $this->students->removeElement($student);
    }

    /**
     * Get students
     *
     *
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Add profesor
     *
     * @param Client $profesor
     *
     * @return Area
     */
    public function addProfesor(Client $profesor)
    {
        $this->profesors[] = $profesor;

        return $this;
    }

    /**
     * Remove profesor
     *
     * @param Client $profesor
     */
    public function removeProfesor(Client $profesor)
    {
        $this->profesor->removeElement($profesor);
    }

    /**
     * Get profesors
     *
     *
     */
    public function getProfesor()
    {
        return $this->profesors;
    }

    /**
     * Add worker
     *
     * @param Client $worker
     *
     * @return Area
     */
    public function addWorker(Client $worker)
    {
        $this->workers[] = $worker;

        return $this;
    }

    /**
     * Remove worker
     *
     * @param Client $worker
     */
    public function removeWorker(Client $worker)
    {
        $this->workers->removeElement($worker);
    }

    /**
     * Get workers
     *
     *
     */
    public function getWorkers()
    {
        return $this->workers;
    }

    /**
     * Set leader
     *
     * @param string $leader
     *
     * @return Area
     */
    public function setLeader($leader)
    {
        $this->leader = $leader;

        return $this;
    }

    /**
     * Get leader
     *
     * @return string
     */
    public function getLeader()
    {
        return $this->leader;
    }


    /**
     * Set type
     *
     * @param string $type
     *
     * @return Area
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


    static function type_AcronimToName($type){
        switch ($type){
            case 'REC': return 'Rectoría';break;
            case 'VRE': return 'Vicerectoría';break;
            case 'DIR': return 'Dirección';break;
            case 'FAC': return 'Facultad';break;
            case 'DEP': return 'Departamento';break;
            default: return 'Tipo de Área No Definido';break;
        }
    }

    static function type_NameToAcronim($type){
        switch ($type){
            case 'Rectoría': return 'REC';break;
            case 'Vicerectoría': return 'VRE';break;
            case 'Dirección': return 'DIR';break;
            case 'Facultad': return 'FAC';break;
            case 'Departamento': return 'DEP';break;
            default: return 'Tipo de Área No Definido';break;
        }
    }


}
