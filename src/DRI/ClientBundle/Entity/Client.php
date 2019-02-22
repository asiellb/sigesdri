<?php

namespace DRI\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DRI\ClientBundle\Entity\Language;
use DRI\ClientBundle\Entity\Organization;
use DRI\ExitBundle\Entity\ExitApplication;
use DRI\PassportBundle\Entity\Passport;
use DRI\UsefulBundle\Entity\Country;
use DRI\UsefulBundle\Entity\School;
use DRI\UsefulBundle\Useful\Useful;
use DRI\UserBundle\Entity\User;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="DRI\ClientBundle\Repository\ClientRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity("ci")
 * @UniqueEntity("email")
 * @UniqueEntity("foreignEmail")
 * @UniqueEntity("clientPicture")
 * @UniqueEntity("fullNameSlug")
 *
 * @Vich\Uploadable
 */
class Client
{
    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    CLIENT'S VARIABLES
     * *
     * ********************************************************************************
     **********************************************************************************/

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ci", type="string", length=11, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=11, max=11)
     * @Assert\Regex("/\d{11}/")
     */
    private $ci;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=20)
     *
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="secondName", type="string", length=20, nullable=true)
     */
    private $secondName;

    /**
     * @var string
     *
     * @ORM\Column(name="firstLastName", type="string", length=20)
     *
     * @Assert\NotBlank()
     */
    private $firstLastName;

    /**
     * @var string
     *
     * @ORM\Column(name="secondLastName", type="string", length=20)
     *
     * @Assert\NotBlank()
     */
    private $secondLastName;

    /**
     * @var string
     *
     * @ORM\Column(name="fullName", type="string", length=100)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="fullNameSlug", type="string", length=100)
     */
    private $fullNameSlug;

    /**
     * @var string
     *
     * @ORM\Column(name="shortName", type="string", length=100)
     */
    private $shortName;

    /**
     * @var string
     *
     * @ORM\Column(name="shortNameSlug", type="string", length=100)
     */
    private $shortNameSlug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=1)
     * @Assert\Choice(choices={"F", "M"})
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="foreignEmail", type="string", length=100, nullable=true, unique=true)
     *
     * @Assert\Email()
     */
    private $foreignEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="privatePhone", type="string", length=14, nullable=true)
     *
     * @Assert\Length(min=8, max=14)
     */
    private $privatePhone;

    /**
     * @var string
     *
     * @ORM\Column(name="cellPhone", type="string", length=14, nullable=true)
     *
     * @Assert\Length(min=8, max=14)
     */
    private $cellPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="clientType", type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length( min=3, max=3)
     * @Assert\Choice(choices={"EST", "DOC", "NOD"})
     */
    private $clientType;

    /**
     * @var string
     *
     * @ORM\Column(name="clientPicture", type="string", length=255, nullable=true, unique=true)
     */
    private $clientPicture;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="clients_images", fileNameProperty="clientPicture")
     */
    private $clientPictureFile;

    /**
     * @var array
     *
     * @ORM\Column(name="languages", type="array", nullable=true)
     */
    private $languages;

    /**
     * @var array
     *
     * @ORM\Column(name="organizations", type="array", nullable=true)
     */
    private $organizations;

    /**
     * @var School
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\School", cascade={"persist"})
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $school;




    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S VARIABLES
     * *
     * ********************************************************************************
     **********************************************************************************/



    /**
     * @var string
     *
     * @ORM\Column(name="mothersName", type="string", length=30, nullable=true)
     */
    private $mothersName;

    /**
     * @var string
     *
     * @ORM\Column(name="fathersName", type="string", length=30, nullable=true)
     */
    private $fathersName;

    /**
     * @var string
     *
     * @ORM\Column(name="civilState", type="string", length=3, nullable=true)
     *
     * @Assert\Choice(choices={"SOL", "CAS", "DIV", "VIU"})
     */
    private $civilState;

    /**
     * @var string
     *
     * @ORM\Column(name="weight", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="height", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="eyesColor", type="string", length=10, nullable=true)
     *
     * @Assert\Choice(choices={"Claros", "Negros", "Pardos"})
     */
    private $eyesColor;

    /**
     * @var string
     *
     * @ORM\Column(name="skinColor", type="string", length=10, nullable=true)
     *
     * @Assert\Choice(choices={"Blanca", "Negra", "Amarilla", "Mulata", "Albina"})
     */
    private $skinColor;

    /**
     * @var string
     *
     * @ORM\Column(name="hairColor", type="string", length=10, nullable=true)
     *
     * @Assert\Choice(choices={"Canoso", "Castaño", "Negro", "Rojo", "Rubio", "Otros"})
     */
    private $hairColor;

    /**
     * @var string
     *
     * @ORM\Column(name="pvs", type="text", nullable=true)
     */
    private $pvs;

    /**
     * @var string
     *
     * @ORM\Column(name="citizenship", type="string", length=30, nullable=true)
     */
    private $citizenship;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Country")
     */
    private $countryBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="stateBirth", type="string", length=30, nullable=true)
     */
    private $stateBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="cityBirth", type="string", length=30, nullable=true)
     */
    private $cityBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="foreignCityBirth", type="string", length=30, nullable=true)
     */
    private $foreignCityBirth;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Country")
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=30, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=30, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="district", type="string", length=30, nullable=true)
     */
    private $district;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=50, nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="highway", type="string", length=50, nullable=true)
     */
    private $highway;

    /**
     * @var string
     *
     * @ORM\Column(name="firstBetween", type="string", length=50, nullable=true)
     */
    private $firstBetween;

    /**
     * @var string
     *
     * @ORM\Column(name="secongBetween", type="string", length=50, nullable=true)
     */
    private $secongBetween;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=6, nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="km", type="string", length=6, nullable=true)
     */
    private $km;

    /**
     * @var string
     *
     * @ORM\Column(name="building", type="string", length=6, nullable=true)
     */
    private $building;

    /**
     * @var string
     *
     * @ORM\Column(name="apartment", type="string", length=6, nullable=true)
     */
    private $apartment;

    /**
     * @var string
     *
     * @ORM\Column(name="cpa", type="string", length=50, nullable=true)
     */
    private $cpa;

    /**
     * @var string
     *
     * @ORM\Column(name="farm", type="string", length=50, nullable=true)
     */
    private $farm;

    /**
     * @var string
     *
     * @ORM\Column(name="town", type="string", length=50, nullable=true)
     */
    private $town;

    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=6, nullable=true)
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="postCode", type="string", length=10, nullable=true)
     */
    private $postCode;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DRI\PassportBundle\Entity\Passport", mappedBy="holder")
     */
    private $passports;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    STUDENT'S VARIABLES
     * *
     * ********************************************************************************
     **********************************************************************************/



    /**
     * @var string
     *
     * @ORM\Column(name="studentsYear", type="string", length=1, nullable=true)
     */
    private $studentsYear;

    /**
     * @var string
     *
     * @ORM\Column(name="studentsPosition", type="string", length=50, nullable=true)
     */
    private $studentsPosition;

    /**
     * @var string
     *
     * @ORM\Column(name="studentsCareer", type="string", length=50, nullable=true)
     */
    private $studentsCareer;

    /**
     * @var School
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\School", inversedBy="students", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id")
     */
    private $studentsSchool;

    /**
     * @var string
     *
     * @ORM\Column(name="studentsState", type="string", length=3, nullable=true)
     *
     * @Assert\Choice(choices={"ACT", "EGR", "BAJ"})
     */
    private $studentsState;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="studentsLastUpdate", type="datetime", nullable=true)
     *
     * @Assert\Date()
     */
    private $studentsLastUpdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="studentsInactiveAt", type="datetime", nullable=true)
     *
     * @Assert\Date()
     */
    private $studentsInactiveAt;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    WORKER'S VARIABLES
     * *
     * ********************************************************************************
     **********************************************************************************/



    /**
     * @var string
     *
     * @ORM\Column(name="workersOccupation", type="string", length=50, nullable=true)
     */
    private $workersOccupation;

    /**
     * @var string
     *
     * @ORM\Column(name="workersSpecialty", type="string", length=50, nullable=true)
     */
    private $workersSpecialty;

    /**
     * @var string
     *
     * @ORM\Column(name="workersEduCategory", type="string", length=3, nullable=true)
     *
     * @Assert\Choice(choices={"ATD", "ADI", "INS", "ASI", "AUX", "TIT"})
     */
    private $workersEduCategory;

    /**
     * @var string
     *
     * @ORM\Column(name="workersSciGrade", type="string", length=3, nullable=true)
     *
     * @Assert\Choice(choices={"LIC", "ING", "ARQ","MSC", "DRC"})
     */
    private $workersSciGrade;

    /**
     * @var string
     *
     * @ORM\Column(name="workersPosition", type="string", length=50, nullable=true)
     */
    private $workersPosition;

    /**
     * @var School
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\School", inversedBy="workers", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id"))
     */
    private $workersSchool;

    /**
     * @var string
     *
     * @ORM\Column(name="workersWorkPlace", type="string", length=50, nullable=true)
     */
    private $workersWorkPlace;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="workersAdmissionDate", type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $workersAdmissionDate;

    /**
     * @var string
     *
     * @ORM\Column(name="workersWorkPhone", type="string", length=14, nullable=true)
     *
     * @Assert\Length(min=8, max=14)
     */
    private $workersWorkPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="workersPay", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $workersPay;

    /**
     * @var string
     *
     * @ORM\Column(name="workersState", type="string", length=3, nullable=true)
     *
     * @Assert\Choice(choices={"ACT", "BAJ"})
     */
    private $workersState;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="workersLastUpdate", type="datetime", nullable=true)
     *
     * @Assert\Date()
     */
    private $workersLastUpdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="workersInactiveAt", type="datetime", nullable=true)
     *
     * @Assert\Date()
     */
    private $workersInactiveAt;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    ENTITY'S VARIABLES
     * *
     * ********************************************************************************
     **********************************************************************************/



    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean")
     */
    private $locked;

    /**
     * @var bool
     *
     * @ORM\Column(name="expired", type="boolean")
     */
    private $expired;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiredAt", type="datetime", nullable=true)
     *
     * @Assert\Date()
     */
    private $expiredAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     *
     * @Assert\Date()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     *
     * @Assert\Date()
     */
    private $updatedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DRI\UserBundle\Entity\User")
     */
    private $createdBy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DRI\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="update_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $lastUpdateBy;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DRI\ExitBundle\Entity\ExitApplication", mappedBy="client")
     */
    private $exitApplications;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DRI\ExitBundle\Entity\Departure", mappedBy="client")
     */
    private $departures;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    CONSTRUCTOR & TOSTRING METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/



    /**
     * Client constructor.
     *
     */
    public function __construct()
    {
        $this->languages = array();
        $this->organizations = array();
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->enabled = true;
        $this->locked = false;
        $this->expired = false;
        $this->exitApplications = new ArrayCollection();
        $this->passports = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getFullName();
    }

    /**
     * Is the given User the owner of this Client?
     *
     * @param User $user
     *
     * @return bool
     */
    public function isOwner(User $user)
    {
        return $user === $this->getCreatedby();
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    CLIENT'S METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/



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
     * Set ci
     *
     * @param string $ci
     *
     * @return Client
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Client
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set secondName
     *
     * @param string $secondName
     *
     * @return Client
     */
    public function setSecondName($secondName)
    {
        $this->secondName = $secondName;

        return $this;
    }

    /**
     * Get secondName
     *
     * @return string
     */
    public function getSecondName()
    {
        return $this->secondName;
    }

    /**
     * Set firstLastName
     *
     * @param string $firstLastName
     *
     * @return Client
     */
    public function setFirstLastName($firstLastName)
    {
        $this->firstLastName = $firstLastName;

        return $this;
    }

    /**
     * Get firstLastName
     *
     * @return string
     */
    public function getFirstLastName()
    {
        return $this->firstLastName;
    }

    /**
     * Set secondLastName
     *
     * @param string $secondLastName
     *
     * @return Client
     */
    public function setSecondLastName($secondLastName)
    {
        $this->secondLastName = $secondLastName;

        return $this;
    }

    /**
     * Get secondLastName
     *
     * @return string
     */
    public function getSecondLastName()
    {
        return $this->secondLastName;
    }

    /**
     * Set fullName
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return Client
     */
    public function setFullName()
    {
        $this->fullName = $this->firstName.' '.$this->secondName.' '.$this->firstLastName.' '.$this->secondLastName;
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
     * Get fullNameSlug
     *
     * @return string
     */
    public function getFullNameSlug()
    {
        return $this->fullNameSlug;
    }

    /**
     * Set shortName
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return Client
     */
    public function setShortName()
    {
        $this->shortName = $this->firstName.' '.$this->firstLastName;
        $this->shortNameSlug = Useful::getSlug($this->shortName);

        return $this;
    }

    /**
     * Get shortName
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->fullName;
    }

    /**
     * Get shortNameSlug
     *
     * @return string
     */
    public function getShortNameSlug()
    {
        return $this->shortNameSlug;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * Set privatePhone
     *
     * @param string $privatePhone
     *
     * @return Client
     */
    public function setPrivatePhone($privatePhone)
    {
        $this->privatePhone = $privatePhone;

        return $this;
    }

    /**
     * Get privatePhone
     *
     * @return string
     */
    public function getPrivatePhone()
    {
        return $this->privatePhone;
    }

    /**
     * Set cellPhone
     *
     * @param string $cellPhone
     *
     * @return Client
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
     * Set clientType
     *
     * @param string $clientType
     *
     * @return Client
     */
    public function setClientType($clientType)
    {
        $this->clientType = $clientType;

        switch ($clientType){
            case 'EST':
                $this->studentsLastUpdate = new \DateTime('now');
                break;
            case 'DOC':
                $this->workersLastUpdate = new \DateTime('now');
                break;
            case 'NOD':
                $this->studentsLastUpdate = new \DateTime('now');
                break;
            default:
                break;
        }

        return $this;
    }

    /**
     * Get clientType
     *
     * @return string
     */
    public function getClientType()
    {
        return $this->clientType;
    }

    /**
     * Set clientPicture
     *
     * @param string $clientPicture
     *
     * @return Client
     */
    public function setClientPicture($clientPicture)
    {
        $this->clientPicture = $clientPicture;

        return $this;
    }

    /**
     * Get clientPicture
     *
     * @return string
     */
    public function getClientPicture()
    {
        return $this->clientPicture;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $clientPicture
     *
     * @return Client
     */
    public function setClientPictureFile(File $clientPicture = null)
    {
        $this->clientPictureFile = $clientPicture;

        if ($clientPicture) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getClientPictureFile()
    {
        return $this->clientPictureFile;
    }

    /**
     * @param Language $language
     * @return $this
     */
    public function addLanguage($language)
    {
        $this->languages[] = $language;

        return $this;
    }

    /**
     * @param Language $language
     * @return $this
     */
    public function removeLanguage($language)
    {
        if (false !== $key = array_search($language, $this->languages, true)) {
            unset($this->languages[$key]);
            $this->languages = array_values($this->languages);
        }

        return $this;
    }

    /**
     * Set languages
     *
     * @param array $languages
     *
     * @return Client
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * Get languages
     *
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param Organization $organization
     * @return $this
     */
    public function addOrganization($organization)
    {
        $this->organizations[] = $organization;

        return $this;
    }

    /**
     * @param Organization $organization
     * @return $this
     */
    public function removeOrganizacion($organization)
    {
        if (false !== $key = array_search($organization, $this->organizations, true)) {
            unset($this->organizations[$key]);
            $this->organizations = array_values($this->organizations);
        }

        return $this;
    }

    /**
     * Set organizations
     *
     * @param array $organizations
     *
     * @return Client
     */
    public function setOrganizations($organizations)
    {
        $this->organizations = $organizations;

        return $this;
    }

    /**
     * Get organizations
     *
     * @return array
     */
    public function getOrganizations()
    {
        return $this->organizations;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/



    /**
     * Set mothersName
     *
     * @param string $mothersName
     *
     * @return Client
     */
    public function setMothersName($mothersName)
    {
        $this->mothersName = $mothersName;

        return $this;
    }

    /**
     * Get mothersName
     *
     * @return string
     */
    public function getMothersName()
    {
        return $this->mothersName;
    }

    /**
     * Set fathersName
     *
     * @param string $fathersName
     *
     * @return Client
     */
    public function setFathersName($fathersName)
    {
        $this->fathersName = $fathersName;

        return $this;
    }

    /**
     * Get fathersName
     *
     * @return string
     */
    public function getFathersName()
    {
        return $this->fathersName;
    }

    /**
     * Set civilState
     *
     * @param string $civilState
     *
     * @return Client
     */
    public function setCivilState($civilState)
    {
        $this->civilState = $civilState;

        return $this;
    }

    /**
     * Get civilState
     *
     * @return string
     */
    public function getCivilState()
    {
        return $this->civilState;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return Client
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set height
     *
     * @param string $height
     *
     * @return Client
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set eyesColor
     *
     * @param string $eyesColor
     *
     * @return Client
     */
    public function setEyesColor($eyesColor)
    {
        $this->eyesColor = $eyesColor;

        return $this;
    }

    /**
     * Get eyesColor
     *
     * @return string
     */
    public function getEyesColor()
    {
        return $this->eyesColor;
    }

    /**
     * Set skinColor
     *
     * @param string $skinColor
     *
     * @return Client
     */
    public function setSkinColor($skinColor)
    {
        $this->skinColor = $skinColor;

        return $this;
    }

    /**
     * Get skinColor
     *
     * @return string
     */
    public function getSkinColor()
    {
        return $this->skinColor;
    }

    /**
     * Set hairColor
     *
     * @param string $hairColor
     *
     * @return Client
     */
    public function setHairColor($hairColor)
    {
        $this->hairColor = $hairColor;

        return $this;
    }

    /**
     * Get hairColor
     *
     * @return string
     */
    public function getHairColor()
    {
        return $this->hairColor;
    }

    /**
     * Set pvs
     *
     * @param string $pvs
     *
     * @return Client
     */
    public function setPvs($pvs)
    {
        $this->pvs = $pvs;

        return $this;
    }

    /**
     * Get pvs
     *
     * @return string
     */
    public function getPvs()
    {
        return $this->pvs;
    }

    /**
     * Set citizenship
     *
     * @param string $citizenship
     *
     * @return Client
     */
    public function setCitizenship($citizenship)
    {
        $this->citizenship = $citizenship;

        return $this;
    }

    /**
     * Get citizenship
     *
     * @return string
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Set countryBirth
     *
     * @param Country $countryBirth
     *
     * @return Client
     */
    public function setCountryBirth(Country $countryBirth = null)
    {
        $this->countryBirth = $countryBirth;

        return $this;
    }

    /**
     * Get countryBirth
     *
     * @return Country
     */
    public function getCountryBirth()
    {
        return $this->countryBirth;
    }

    /**
     * Set stateBirth
     *
     * @param string $stateBirth
     *
     * @return Client
     */
    public function setStateBirth($stateBirth)
    {
        $this->stateBirth = $stateBirth;

        return $this;
    }

    /**
     * Get stateBirth
     *
     * @return string
     */
    public function getStateBirth()
    {
        return $this->stateBirth;
    }

    /**
     * Set cityBirth
     *
     * @param string $cityBirth
     *
     * @return Client
     */
    public function setCityBirth($cityBirth)
    {
        $this->cityBirth = $cityBirth;

        return $this;
    }

    /**
     * Get cityBirth
     *
     * @return string
     */
    public function getCityBirth()
    {
        return $this->cityBirth;
    }

    /**
     * Set foreignCityBirth
     *
     * @param string $foreignCityBirth
     *
     * @return Client
     */
    public function setForeignCityBirth($foreignCityBirth)
    {
        $this->foreignCityBirth = $foreignCityBirth;

        return $this;
    }

    /**
     * Get foreignCityBirth
     *
     * @return string
     */
    public function getForeignCityBirth()
    {
        return $this->foreignCityBirth;
    }

    /**
     * Set country
     *
     * @param Country $country
     *
     * @return Client
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
     * Set state
     *
     * @param string $state
     *
     * @return Client
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Client
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set district
     *
     * @param string $district
     *
     * @return Client
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Client
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set highway
     *
     * @param string $highway
     *
     * @return Client
     */
    public function setHighway($highway)
    {
        $this->highway = $highway;

        return $this;
    }

    /**
     * Get highway
     *
     * @return string
     */
    public function getHighway()
    {
        return $this->highway;
    }

    /**
     * Set firstBetween
     *
     * @param string $firstBetween
     *
     * @return Client
     */
    public function setFirstBetween($firstBetween)
    {
        $this->firstBetween = $firstBetween;

        return $this;
    }

    /**
     * Get firstBetween
     *
     * @return string
     */
    public function getFirstBetween()
    {
        return $this->firstBetween;
    }

    /**
     * Set secongBetween
     *
     * @param string $secongBetween
     *
     * @return Client
     */
    public function setSecongBetween($secongBetween)
    {
        $this->secongBetween = $secongBetween;

        return $this;
    }

    /**
     * Get secongBetween
     *
     * @return string
     */
    public function getSecongBetween()
    {
        return $this->secongBetween;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return Client
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set km
     *
     * @param string $km
     *
     * @return Client
     */
    public function setKm($km)
    {
        $this->km = $km;

        return $this;
    }

    /**
     * Get km
     *
     * @return string
     */
    public function getKm()
    {
        return $this->km;
    }

    /**
     * Set building
     *
     * @param string $building
     *
     * @return Client
     */
    public function setBuilding($building)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building
     *
     * @return string
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set apartment
     *
     * @param string $apartment
     *
     * @return Client
     */
    public function setApartment($apartment)
    {
        $this->apartment = $apartment;

        return $this;
    }

    /**
     * Get apartment
     *
     * @return string
     */
    public function getApartment()
    {
        return $this->apartment;
    }

    /**
     * Set cpa
     *
     * @param string $cpa
     *
     * @return Client
     */
    public function setCpa($cpa)
    {
        $this->cpa = $cpa;

        return $this;
    }

    /**
     * Get cpa
     *
     * @return string
     */
    public function getCpa()
    {
        return $this->cpa;
    }

    /**
     * Set farm
     *
     * @param string $farm
     *
     * @return Client
     */
    public function setFarm($farm)
    {
        $this->farm = $farm;

        return $this;
    }

    /**
     * Get farm
     *
     * @return string
     */
    public function getFarm()
    {
        return $this->farm;
    }

    /**
     * Set town
     *
     * @param string $town
     *
     * @return Client
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get town
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set area
     *
     * @param string $area
     *
     * @return Client
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set postCode
     *
     * @param string $postCode
     *
     * @return Client
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * Get postCode
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * Add passport
     *
     * @param Passport $passport
     *
     * @return Client
     */
    public function addPassport(Passport $passport)
    {
        $this->passports[] = $passport;

        return $this;
    }

    /**
     * Remove passport
     *
     * @param Passport $passport
     */
    public function removePassport(Passport $passport)
    {
        $this->passports->removeElement($passport);
    }

    /**
     * Get passports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPassports()
    {
        return $this->passports;
    }





    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    STUDENT'S METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/



    /**
     * Set studentsYear
     *
     * @param string $studentsYear
     *
     * @return Client
     */
    public function setStudentsYear($studentsYear)
    {
        $this->studentsYear = $studentsYear;

        return $this;
    }

    /**
     * Get studentsYear
     *
     * @return string
     */
    public function getStudentsYear()
    {
        return $this->studentsYear;
    }

    /**
     * Set studentsPosition
     *
     * @param string $studentsPosition
     *
     * @return Client
     */
    public function setStudentsPosition($studentsPosition)
    {
        $this->studentsPosition = $studentsPosition;

        return $this;
    }

    /**
     * Get studentsPosition
     *
     * @return string
     */
    public function getStudentsPosition()
    {
        return $this->studentsPosition;
    }

    /**
     * Set studentsCareer
     *
     * @param string $studentsCareer
     *
     * @return Client
     */
    public function setStudentsCareer($studentsCareer)
    {
        $this->studentsCareer = $studentsCareer;

        return $this;
    }

    /**
     * Get studentsCareer
     *
     * @return string
     */
    public function getStudentsCareer()
    {
        return $this->studentsCareer;
    }

    /**
     * Set studentsSchool
     *
     * @param School $studentsSchool
     *
     * @return Client
     */
    public function setStudentsSchool(School $studentsSchool = null)
    {
        $this->studentsSchool = $studentsSchool;

        return $this;
    }

    /**
     * Get studentsSchool
     *
     * @return School
     */
    public function getStudentsSchool()
    {
        return $this->studentsSchool;
    }

    /**
     * Set studentsState
     *
     * @param string $studentsState
     *
     * @return Client
     */
    public function setStudentsState($studentsState)
    {
        $this->studentsState = $studentsState;
        if ($studentsState){
            $this->studentsLastUpdate = new \DateTime('now');
        }

        if ($studentsState == 'EGR' || $studentsState == 'BAJ'){
            $this->studentsInactiveAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * Get studentsState
     *
     * @return string
     */
    public function getStudentsState()
    {
        return $this->studentsState;
    }

    /**
     * Get studentsLastUpdate
     *
     * @return \DateTime
     */
    public function getStudentsLastUpdate()
    {
        return $this->studentsLastUpdate;
    }

    /**
     * Get studentsInactiveAt
     *
     * @return \DateTime
     */
    public function getStudentsInactiveAt()
    {
        return $this->studentsInactiveAt;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    WORKER'S METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/



    /**
     * Set workersOccupation
     *
     * @param string $workersOccupation
     *
     * @return Client
     */
    public function setWorkersOccupation($workersOccupation)
    {
        $this->workersOccupation = $workersOccupation;

        return $this;
    }

    /**
     * Get workersOccupation
     *
     * @return string
     */
    public function getWorkersOccupation()
    {
        return $this->workersOccupation;
    }

    /**
     * Set workersSpecialty
     *
     * @param string $workersSpecialty
     *
     * @return Client
     */
    public function setWorkersSpecialty($workersSpecialty)
    {
        $this->workersSpecialty = $workersSpecialty;

        return $this;
    }

    /**
     * Get workersSpecialty
     *
     * @return string
     */
    public function getWorkersSpecialty()
    {
        return $this->workersSpecialty;
    }

    /**
     * Set workersEduCategory
     *
     * @param string $workersEduCategory
     *
     * @return Client
     */
    public function setWorkersEduCategory($workersEduCategory)
    {
        $this->workersEduCategory = $workersEduCategory;

        return $this;
    }

    /**
     * Get workersEduCategory
     *
     * @return string
     */
    public function getWorkersEduCategory()
    {
        return $this->workersEduCategory;
    }

    /**
     * Set workersSciGrade
     *
     * @param string $workersSciGrade
     *
     * @return Client
     */
    public function setWorkersSciGrade($workersSciGrade)
    {
        $this->workersSciGrade = $workersSciGrade;

        return $this;
    }

    /**
     * Get workersSciGrade
     *
     * @return string
     */
    public function getWorkersSciGrade()
    {
        return $this->workersSciGrade;
    }

    /**
     * Set workersPosition
     *
     * @param string $workersPosition
     *
     * @return Client
     */
    public function setWorkersPosition($workersPosition)
    {
        $this->workersPosition = $workersPosition;

        return $this;
    }

    /**
     * Get workersPosition
     *
     * @return string
     */
    public function getWorkersPosition()
    {
        return $this->workersPosition;
    }

    /**
     * Set workersSchool
     *
     * @param School $workersSchool
     *
     * @return Client
     */
    public function setWorkersSchool(School $workersSchool = null)
    {
        $this->workersSchool = $workersSchool;

        return $this;
    }

    /**
     * Get workersSchool
     *
     * @return School
     */
    public function getWorkersSchool()
    {
        return $this->workersSchool;
    }

    /**
     * Set workersWorkPlace
     *
     * @param string $workersWorkPlace
     *
     * @return Client
     */
    public function setWorkersWorkPlace($workersWorkPlace)
    {
        $this->workersWorkPlace = $workersWorkPlace;

        return $this;
    }

    /**
     * Get workersWorkPlace
     *
     * @return string
     */
    public function getWorkersWorkPlace()
    {
        return $this->workersWorkPlace;
    }

    /**
     * Set workersAdmissionDate
     *
     * @param \DateTime $workersAdmissionDate
     *
     * @return Client
     */
    public function setWorkersAdmissionDate($workersAdmissionDate)
    {
        $this->workersAdmissionDate = $workersAdmissionDate;

        return $this;
    }

    /**
     * Get workersAdmissionDate
     *
     * @return \DateTime
     */
    public function getWorkersAdmissionDate()
    {
        return $this->workersAdmissionDate;
    }

    /**
     * Set workersWorkPhone
     *
     * @param string $workersWorkPhone
     *
     * @return Client
     */
    public function setWorkersWorkPhone($workersWorkPhone)
    {
        $this->workersWorkPhone = $workersWorkPhone;

        return $this;
    }

    /**
     * Get workersWorkPhone
     *
     * @return string
     */
    public function getWorkersWorkPhone()
    {
        return $this->workersWorkPhone;
    }

    /**
     * Set workersPay
     *
     * @param string $workersPay
     *
     * @return Client
     */
    public function setWorkersPay($workersPay)
    {
        $this->workersPay = $workersPay;

        return $this;
    }

    /**
     * Get workersPay
     *
     * @return string
     */
    public function getWorkersPay()
    {
        return $this->workersPay;
    }

    /**
     * Set workersState
     *
     * @param string $workersState
     *
     * @return Client
     */
    public function setWorkersState($workersState)
    {
        $this->workersState = $workersState;

        if ($workersState){
            $this->workersLastUpdate = new \DateTime('now');
        }

        if ($workersState == 'BAJ'){
            $this->workersInactiveAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * Get workersState
     *
     * @return string
     */
    public function getWorkersState()
    {
        return $this->workersState;
    }

    /**
     * Get workersLastUpdate
     *
     * @return \DateTime
     */
    public function getWorkersLastUpdate()
    {
        return $this->workersLastUpdate;
    }

    /**
     * Get workersInactiveAt
     *
     * @return \DateTime
     */
    public function getWorkersInactiveAt()
    {
        return $this->workersInactiveAt;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    ENTITY'S METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/



    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Client
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     *
     * @return Client
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return bool
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set expired
     *
     * @param boolean $expired
     *
     * @return Client
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;

        if ($expired){
            $this->expiredAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * Get expired
     *
     * @return bool
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * Get expiredAt
     *
     * @return \DateTime
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist
     *
     * @return Client
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
     * @return Client
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
     * Set createdBy.
     *
     * @param User $createdBy
     *
     *
     * @return $this
     */
    public function setCreatedBy(User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set fullNameSlug
     *
     * @param string $fullNameSlug
     *
     * @return Client
     */
    public function setFullNameSlug($fullNameSlug)
    {
        $this->fullNameSlug = $fullNameSlug;

        return $this;
    }

    /**
     * Set studentsLastUpdate
     *
     * @param \DateTime $studentsLastUpdate
     *
     * @return Client
     */
    public function setStudentsLastUpdate($studentsLastUpdate)
    {
        $this->studentsLastUpdate = $studentsLastUpdate;

        return $this;
    }

    /**
     * Set studentsInactiveAt
     *
     * @param \DateTime $studentsInactiveAt
     *
     * @return Client
     */
    public function setStudentsInactiveAt($studentsInactiveAt)
    {
        $this->studentsInactiveAt = $studentsInactiveAt;

        return $this;
    }

    /**
     * Set workersLastUpdate
     *
     * @param \DateTime $workersLastUpdate
     *
     * @return Client
     */
    public function setWorkersLastUpdate($workersLastUpdate)
    {
        $this->workersLastUpdate = $workersLastUpdate;

        return $this;
    }

    /**
     * Set workersInactiveAt
     *
     * @param \DateTime $workersInactiveAt
     *
     * @return Client
     */
    public function setWorkersInactiveAt($workersInactiveAt)
    {
        $this->workersInactiveAt = $workersInactiveAt;

        return $this;
    }

    /**
     * Set expiredAt
     *
     * @param \DateTime $expiredAt
     *
     * @return Client
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * Set lastUpdateBy
     *
     * @param User $lastUpdateBy
     *
     * @return Client
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

    /**
     * Add exitApplication
     *
     * @param ExitApplication $exitApplication
     *
     * @return Client
     */
    public function addExitApplication(ExitApplication $exitApplication)
    {
        $this->exitApplications[] = $exitApplication;

        return $this;
    }

    /**
     * Remove exitApplication
     *
     * @param ExitApplication $exitApplication
     */
    public function removeExitApplication(ExitApplication $exitApplication)
    {
        $this->exitApplications->removeElement($exitApplication);
    }

    /**
     * Get exitApplications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExitApplications()
    {
        return $this->exitApplications;
    }

    /**
     * Set school
     *
     * @param \DRI\UsefulBundle\Entity\School $school
     *
     * @return Client
     */
    public function setSchool(\DRI\UsefulBundle\Entity\School $school = null)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return \DRI\UsefulBundle\Entity\School
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Add departure
     *
     * @param \DRI\ExitBundle\Entity\Departure $departure
     *
     * @return Client
     */
    public function addDeparture(\DRI\ExitBundle\Entity\Departure $departure)
    {
        $this->departures[] = $departure;

        return $this;
    }

    /**
     * Remove departure
     *
     * @param \DRI\ExitBundle\Entity\Departure $departure
     */
    public function removeDeparture(\DRI\ExitBundle\Entity\Departure $departure)
    {
        $this->departures->removeElement($departure);
    }

    /**
     * Get departures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDepartures()
    {
        return $this->departures;
    }
}
