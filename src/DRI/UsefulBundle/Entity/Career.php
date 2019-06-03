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
 * Career
 *
 * @ORM\Table(name="usf_career")
 * @ORM\Entity(repositoryClass="DRI\UsefulBundle\Repository\CareerRepository")
 */
class Career
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
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @var Area
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Area", inversedBy="carrers", cascade={"persist"})
     * @ORM\JoinColumn(name="area_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $leader;

    /**
     * @ORM\OneToMany(targetEntity="DRI\ClientBundle\Entity\Client", mappedBy="studentsCareer")
     */
    private $students;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new ArrayCollection();
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


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Career
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
     * Set leader
     *
     * @param string $leader
     *
     * @return Career
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
     * Set area
     *
     * @param \DRI\UsefulBundle\Entity\Area $area
     *
     * @return Career
     */
    public function setArea(\DRI\UsefulBundle\Entity\Area $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return \DRI\UsefulBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Add student
     *
     * @param Client $student
     *
     * @return Career
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudents()
    {
        return $this->students;
    }
}
