<?php

namespace DRI\ForeingStudentBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DRI\UsefulBundle\Entity\Country;
use DRI\UsefulBundle\Entity\Area;
use DRI\UsefulBundle\Entity\Course;
use DRI\UsefulBundle\Useful\Useful;
use DRI\UserBundle\Entity\User;


/**
 * Postgraduate
 *
 * @ORM\Table(name="fst_postgraduate")
 * @ORM\Entity(repositoryClass="DRI\ForeingStudentBundle\Repository\PostgraduateRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity("ci")
 * @UniqueEntity("fullNameSlug")
 * @UniqueEntity("email")
 * @UniqueEntity("foreignEmail")
 * @UniqueEntity("picture")
 *
 * @Vich\Uploadable
 */
class Postgraduate
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
     * @ORM\Column(type="string", length=11, unique=true)
     */
    private $ci;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $names;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $lastNames;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=200)
     */
    private $fullNameSlug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=1)
     * @Assert\Choice(choices={"F", "M"})
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true, unique=true)
     *
     * @Assert\Email()
     */
    private $foreignEmail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=14, nullable=true)
     *
     * @Assert\Length(min=8, max=14)
     */
    private $cellPhone;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $passportNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length( min=3, max=3)
     * @Assert\Choice(choices={"DOC", "MAE", "ESP", "CCO"})
     */
    private $courseType;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Course", cascade={"persist"})
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $course;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $shortCourse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $entryDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $expiryDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $picture;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="postgraduates_pictures", fileNameProperty="picture")
     */
    private $pictureFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Date()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Date()
     */
    private $updatedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DRI\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="created_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $createdBy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DRI\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="updated_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $lastUpdateBy;


    /**
     * Client constructor.
     *
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    public function __toString()
    {
        return $this->getFullName();
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
     * Set ci
     *
     * @param string $ci
     *
     * @return Postgraduate
     */
    public function setCi($ci)
    {
        $this->ci = $ci;

        return $this;
    }

    /**
     * Get ci
     *
     * @return string
     */
    public function getCi()
    {
        return $this->ci;
    }

    /**
     * Set names
     *
     * @param string $names
     *
     * @return Postgraduate
     */
    public function setNames($names)
    {
        $this->names = $names;

        return $this;
    }

    /**
     * Get names
     *
     * @return string
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * Set lastNames
     *
     * @param string $lastNames
     *
     * @return Postgraduate
     */
    public function setLastNames($lastNames)
    {
        $this->lastNames = $lastNames;

        return $this;
    }

    /**
     * Get lastNames
     *
     * @return string
     */
    public function getLastNames()
    {
        return $this->lastNames;
    }

    /**
     * Set fullName
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return Postgraduate
     */
    public function setFullName()
    {
        $this->fullName = $this->names.' '.$this->lastNames;
        $this->fullNameSlug = Useful::getSlug($this->fullName);

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set fullNameSlug
     *
     * @param string $fullNameSlug
     *
     * @return Postgraduate
     */
    public function setFullNameSlug($fullNameSlug)
    {
        $this->fullNameSlug = $fullNameSlug;

        return $this;
    }

    /**
     * Get fullNameSlug
     *
     * @return string
     */
    public function getFullNameSlug()
    {
        return $this->fullNameSlug;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Postgraduate
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Postgraduate
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Postgraduate
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set foreignEmail
     *
     * @param string $foreignEmail
     *
     * @return Postgraduate
     */
    public function setForeignEmail($foreignEmail)
    {
        $this->foreignEmail = $foreignEmail;

        return $this;
    }

    /**
     * Get foreignEmail
     *
     * @return string
     */
    public function getForeignEmail()
    {
        return $this->foreignEmail;
    }

    /**
     * Set cellPhone
     *
     * @param string $cellPhone
     *
     * @return Postgraduate
     */
    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $cellPhone;

        return $this;
    }

    /**
     * Get cellPhone
     *
     * @return string
     */
    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    /**
     * Set courseType
     *
     * @param string $courseType
     *
     * @return Postgraduate
     */
    public function setCourseType($courseType)
    {
        $this->courseType = $courseType;

        return $this;
    }

    /**
     * Get courseType
     *
     * @return string
     */
    public function getCourseType()
    {
        return $this->courseType;
    }

    /**
     * Set passportNumber
     *
     * @param string $passportNumber
     *
     * @return Postgraduate
     */
    public function setPassportNumber($passportNumber)
    {
        $this->passportNumber = $passportNumber;

        return $this;
    }

    /**
     * Get passportNumber
     *
     * @return string
     */
    public function getPassportNumber()
    {
        return $this->passportNumber;
    }

    /**
     * Set entryDate
     *
     * @param \DateTime $entryDate
     *
     * @return Postgraduate
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * Get entryDate
     *
     * @return \DateTime
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return Postgraduate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Postgraduate
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Postgraduate
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $picture
     *
     * @return Postgraduate
     */
    public function setPictureFile(File $picture = null)
    {
        $this->pictureFile = $picture;

        if ($picture) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist
     *
     * @return Postgraduate
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime('now');

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @ORM\PreUpdate
     *
     * @return Postgraduate
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime('now');

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set country
     *
     * @param Country $country
     *
     * @return Postgraduate
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \DRI\UsefulBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set course
     *
     * @param Course $course
     *
     * @return Postgraduate
     */
    public function setCourse(Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set shortCourse
     *
     * @param string $shortCourse
     *
     * @return Postgraduate
     */
    public function setShortCourse($shortCourse)
    {
        $this->shortCourse = $shortCourse;

        return $this;
    }

    /**
     * Get shortCourse
     *
     * @return string
     */
    public function getShortCourse()
    {
        return $this->shortCourse;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
     *
     * @return Postgraduate
     */
    public function setCreatedBy(User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \DRI\UserBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set lastUpdateBy
     *
     * @param User $lastUpdateBy
     *
     * @return Postgraduate
     */
    public function setLastUpdateBy(User $lastUpdateBy = null)
    {
        $this->lastUpdateBy = $lastUpdateBy;

        return $this;
    }

    /**
     * Get lastUpdateBy
     *
     * @return \DRI\UserBundle\Entity\User
     */
    public function getLastUpdateBy()
    {
        return $this->lastUpdateBy;
    }
}
