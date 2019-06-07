<?php

namespace DRI\ForeingStudentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DateTime;
use Exception;

use DRI\UsefulBundle\Entity\Country;
use DRI\UsefulBundle\Entity\Career;
use DRI\UsefulBundle\Useful\Useful;
use DRI\UserBundle\Entity\User;

/**
 * Undergraduate
 *
 * @ORM\Table(name="fst_undergraduate")
 * @ORM\Entity(repositoryClass="DRI\ForeingStudentBundle\Repository\UndergraduateRepository")
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
class Undergraduate
{

    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    UNDERGRADUATE'S CONSTANTS
     * *
     * ********************************************************************************
     **********************************************************************************/

    public static $UNDERGRADUATE_TYPE = [
        'BEC' => 'Becario',
        'AUT' => 'Autofinanciado',
        'FCO' => 'Financiado por Convenio',
    ];

    public static $UNDERGRADUATE_TYPE_CHOICE = [
        'Becario'                  =>'BEC',
        'Autofinanciado'            =>'AUT',
        'Financiado por Convenio' =>'FCO',
    ];

    public static $UNDERGRADUATE_YEAR = [
        'PRE' => 'Preparatoria',
        '1RO' => '1RO',
        '2DO' => '2DO',
        '3RO' => '3RO',
        '4TO' => '4TO',
        '5TO' => '5TO',
    ];

    public static $UNDERGRADUATE_YEAR_CHOICE = [
        'Preparatoria' => 'PRE',
        '1RO' => '1RO',
        '2DO' => '2DO',
        '3RO' => '3RO',
        '4TO' => '4TO',
        '5TO' => '5TO',
    ];



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    UNDERGRADUATE'S VARIABLES
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
     * @var DateTime
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
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length( min=3, max=3)
     * @Assert\Choice(choices={"AUT", "FCO", "BEC"})
     */
    private $type;

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
     * @var Career
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Career", cascade={"persist"})
     * @ORM\JoinColumn(name="career_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $career;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     */
    private $entryDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     */
    private $expiryDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $picture;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="undergraduates_pictures", fileNameProperty="picture")
     */
    private $pictureFile;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Date()
     */
    private $createdAt;

    /**
     * @var DateTime
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



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    UNDERGRADUATE'S CONSTRUCTOR & TO_STRING METHOD
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * Client constructor.
     *
     */
    public function __construct()
    {
        $this->createdAt = new DateTime('now');
        $this->updatedAt = new DateTime('now');
    }

    public function __toString()
    {
        return $this->getFullName();
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    UNDERGRADUATE'S GET & SET METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/




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
     * @return Undergraduate
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
     * @return Undergraduate
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
     * @return Undergraduate
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
     * @return Undergraduate
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
     * @return Undergraduate
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
     * @param DateTime $birthday
     *
     * @return Undergraduate
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return DateTime
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
     * @return Undergraduate
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
     * @return Undergraduate
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
     * @return Undergraduate
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
     * @return Undergraduate
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
     * Set type
     *
     * @param string $type
     *
     * @return Undergraduate
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

    /**
     * Set passportNumber
     *
     * @param string $passportNumber
     *
     * @return Undergraduate
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
     * @param DateTime $entryDate
     *
     * @return Undergraduate
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * Get entryDate
     *
     * @return DateTime
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }

    /**
     * Set expiryDate
     *
     * @param DateTime $expiryDate
     *
     * @return Undergraduate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set year
     *
     * @param string $year
     *
     * @return Undergraduate
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Undergraduate
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
     * @param File|UploadedFile $picture
     *
     * @return Undergraduate
     * @throws Exception
     */
    public function setPictureFile(File $picture = null)
    {
        $this->pictureFile = $picture;

        if ($picture) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTime('now');
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
     * @return Undergraduate
     * @throws Exception
     */
    public function setCreatedAt()
    {
        $this->createdAt = new DateTime('now');

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return DateTime
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
     * @return Undergraduate
     * @throws Exception
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new DateTime('now');

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return DateTime
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
     * @return Undergraduate
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set career
     *
     * @param Career $career
     *
     * @return Undergraduate
     */
    public function setCareer(Career $career = null)
    {
        $this->career = $career;

        return $this;
    }

    /**
     * Get career
     *
     * @return Career
     */
    public function getCareer()
    {
        return $this->career;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
     *
     * @return Undergraduate
     */
    public function setCreatedBy(User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return User
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
     * @return Undergraduate
     */
    public function setLastUpdateBy(User $lastUpdateBy = null)
    {
        $this->lastUpdateBy = $lastUpdateBy;

        return $this;
    }

    /**
     * Get lastUpdateBy
     *
     * @return User
     */
    public function getLastUpdateBy()
    {
        return $this->lastUpdateBy;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    UNDERGRADUATE'S ADITIONALS METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * @param $type
     * @return string
     */
    static function type_AcronimToName($type){
        switch ($type){
            case 'BEC': return 'Becario';break;
            case 'AUT': return 'Autofinanciado';break;
            case 'FCO': return 'Financiados por Convenio';break;
            default: return 'Tipo de Estudiante No Definido';break;
        }
    }

    /**
     * @param $type
     * @return string
     */
    static function type_NameToAcronim($type){
        switch ($type){
            case 'Becario': return 'BEC';break;
            case 'Autofinanciado': return 'AUT';break;
            case 'Financiado por Convenio': return 'FCO';break;
            default: return 'Tipo de Estudiante No Definido';break;
        }
    }

    /**
     * @param $year
     * @return string
     */
    static function year_AcronimToName($year){
        switch ($year){
            case 'PRE': return 'Preparatoria';break;
            case '1RO': return '1RO';break;
            case '2DO': return '2DO';break;
            case '3RO': return '3RO';break;
            case '4TO': return '4TO';break;
            case '5TO': return '5TO';break;
            default: return 'Año de Estudiante No Definido';break;
        }
    }

    /**
     * @param $year
     * @return string
     */
    static function year_NameToAcronim($year){
        switch ($year){
            case 'Preparatoria': return 'PRE';break;
            case '1RO': return '1RO';break;
            case '2DO': return '2DO';break;
            case '3RO': return '3RO';break;
            case '4TO': return '4TO';break;
            case '5TO': return '5TO';break;
            default: return 'Año de Estudiante No Definido';break;
        }
    }

}
