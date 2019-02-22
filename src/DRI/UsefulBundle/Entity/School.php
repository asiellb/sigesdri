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

/**
 * Facultad
 *
 * @ORM\Table(name="school")
 * @ORM\Entity(repositoryClass="DRI\UsefulBundle\Repository\SchoolRepository")
 * @UniqueEntity("name")
 * @Vich\Uploadable
 */
class School
{
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
     * @ORM\Column(name="name", type="string", length=50, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=1,max=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="leader", type="string", length=100, nullable=true)
     * @Assert\Length(min=0,max=100)
     */
    private $leader;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="DRI\ClientBundle\Entity\Client", mappedBy="studentsSchool")
     */
    private $students;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="DRI\ClientBundle\Entity\Client", mappedBy="workersSchool")
     */
    private $workers;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = array();
        $this->workers = array();
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
     * @return School
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
     * Add student
     *
     * @param Client $student
     *
     * @return School
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
     * Add worker
     *
     * @param Client $worker
     *
     * @return School
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
     * @return School
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
}
