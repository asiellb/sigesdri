<?php

namespace DRI\AgreementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DRI\ClientBundle\Entity\Language;
use DRI\ClientBundle\Entity\Organization;
use DRI\PassportBundle\Entity\Passport;
use DRI\PassportBundle\Entity\Application as PassportApplication;
use DRI\ExitBundle\Entity\Departure;
use DRI\ExitBundle\Entity\Application as ExitApplication;
use DRI\UsefulBundle\Entity\Country;
use DRI\UsefulBundle\Entity\Area;
use DRI\UserBundle\Entity\User;
use DRI\UsefulBundle\Useful\Useful;
use DRI\AgreementBundle\Entity\Institutional;
use DRI\AgreementBundle\Entity\Institution;


/**
 * Application
 *
 * @ORM\Table(name="agr_application")
 * @ORM\Entity(repositoryClass="DRI\AgreementBundle\Repository\ApplicationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity("number")
 * @UniqueEntity("numberSlug")
 *
 * @Vich\Uploadable
 */
class Application
{

    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    AGREEMENT'S APPLICATION CONSTANTS
     * *
     * ********************************************************************************
     **********************************************************************************/

    const AGREEMENT_APPLICATION_STATE = [
        'CON' =>'Confeccionada',
        'APR' =>'Aprobada',
        'REC' =>'Rechazada',
    ];

    const AGREEMENT_APPLICATION_STATE_CHOICE = [
        'Confeccionada'  =>'CON',
        'Aprobada'       =>'APR',
        'Rechazada'      =>'REC',
    ];



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    AGREEMENT'S APPLICATION VARIABLES
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
     * @ORM\Column(type="string", unique=true)
     *
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     *
     */
    private $numberSlug;

    /**
     * @var Institution
     *
     * @ORM\ManyToOne(targetEntity="DRI\AgreementBundle\Entity\Institution")
     */
    private $institution;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $background;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $objetives;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $basement;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $commitments;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    private $validity;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $memberForCubanPart;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $memberForForeignPart;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $results;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $expenses;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"CON", "APR", "REC"})
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $confirmDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $rejectDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rejectReasons;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $closed;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $used;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime()
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
     * @ORM\OneToOne(targetEntity="DRI\AgreementBundle\Entity\Institutional", mappedBy="application")
     */
    private $institutional;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    AGREEMENT'S APPLICATION CONSTRUCTOR & TO_STRING METHOD
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * Application constructor.
     *
     */
    public function __construct()
    {
        $this->state                = 'CON';
        $this->closed               = false;
        $this->used                 = false;
        $this->createdAt            = new \DateTime('now');
        $this->updatedAt            = new \DateTime('now');
    }

    public function __toString()
    {
        $number = $this->getNumber();

        return $number;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    AGREEMENT'S APPLICATION ENTITY METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/




    public function isConfirmed(){
        if($this->getState() == 'CON'){
            return true;
        }
        return false;
    }

    public function isRegected(){
        if($this->getState() == 'REC'){
            return true;
        }
        return false;
    }

    public function isClosed(){
        if($this->closed){
            return true;
        }
        return false;
    }

    public function isUsed(){
        if($this->used){
            return true;
        }
        return false;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    AGREEMENT'S APPLICATION GET & SET METHODS
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
     * Set number
     *
     * @ORM\PrePersist
     *
     * @return Application
     */
    public function setNumber()
    {
        $aInst = $this->getInstitution()->getAcronym();
        $aDate = date_format($this->getCreatedAt(), "ymd");

        $this->number       = $aInst.'-'.$aDate;
        $this->numberSlug   = Useful::getSlug($this->number);

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
     * Set numberSlug
     *
     * @param string $numberSlug
     *
     * @return Application
     */
    public function setNumberSlug($numberSlug)
    {
        $this->numberSlug = $numberSlug;

        return $this;
    }

    /**
     * Get numberSlug
     *
     * @return string
     */
    public function getNumberSlug()
    {
        return $this->numberSlug;
    }

    /**
     * Set background
     *
     * @param string $background
     *
     * @return Application
     */
    public function setBackground($background)
    {
        $this->background = $background;

        return $this;
    }

    /**
     * Get background
     *
     * @return string
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * Set objetives
     *
     * @param string $objetives
     *
     * @return Application
     */
    public function setObjetives($objetives)
    {
        $this->objetives = $objetives;

        return $this;
    }

    /**
     * Get objetives
     *
     * @return string
     */
    public function getObjetives()
    {
        return $this->objetives;
    }

    /**
     * Set basement
     *
     * @param string $basement
     *
     * @return Application
     */
    public function setBasement($basement)
    {
        $this->basement = $basement;

        return $this;
    }

    /**
     * Get basement
     *
     * @return string
     */
    public function getBasement()
    {
        return $this->basement;
    }

    /**
     * Set commitments
     *
     * @param string $commitments
     *
     * @return Application
     */
    public function setCommitments($commitments)
    {
        $this->commitments = $commitments;

        return $this;
    }

    /**
     * Get commitments
     *
     * @return string
     */
    public function getCommitments()
    {
        return $this->commitments;
    }

    /**
     * Set validity
     *
     * @param string $validity
     *
     * @return Application
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;

        return $this;
    }

    /**
     * Get validity
     *
     * @return string
     */
    public function getValidity()
    {
        return $this->validity;
    }

    /**
     * Set memberForCubanPart
     *
     * @param string $memberForCubanPart
     *
     * @return Application
     */
    public function setMemberForCubanPart($memberForCubanPart)
    {
        $this->memberForCubanPart = $memberForCubanPart;

        return $this;
    }

    /**
     * Get memberForCubanPart
     *
     * @return string
     */
    public function getMemberForCubanPart()
    {
        return $this->memberForCubanPart;
    }

    /**
     * Set memberForForeignPart
     *
     * @param string $memberForForeignPart
     *
     * @return Application
     */
    public function setMemberForForeignPart($memberForForeignPart)
    {
        $this->memberForForeignPart = $memberForForeignPart;

        return $this;
    }

    /**
     * Get memberForForeignPart
     *
     * @return string
     */
    public function getMemberForForeignPart()
    {
        return $this->memberForForeignPart;
    }

    /**
     * Set results
     *
     * @param string $results
     *
     * @return Application
     */
    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }

    /**
     * Get results
     *
     * @return string
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set expenses
     *
     * @param string $expenses
     *
     * @return Application
     */
    public function setExpenses($expenses)
    {
        $this->expenses = $expenses;

        return $this;
    }

    /**
     * Get expenses
     *
     * @return string
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Application
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
     * Set confirmDate
     *
     * @param \DateTime $confirmDate
     *
     * @return Application
     */
    public function setConfirmDate($confirmDate)
    {
        $this->confirmDate = $confirmDate;

        return $this;
    }

    /**
     * Get confirmDate
     *
     * @return \DateTime
     */
    public function getConfirmDate()
    {
        return $this->confirmDate;
    }

    /**
     * Set rejectDate
     *
     * @param \DateTime $rejectDate
     *
     * @return Application
     */
    public function setRejectDate($rejectDate)
    {
        $this->rejectDate = $rejectDate;

        return $this;
    }

    /**
     * Get rejectDate
     *
     * @return \DateTime
     */
    public function getRejectDate()
    {
        return $this->rejectDate;
    }

    /**
     * Set rejectReasons
     *
     * @param string $rejectReasons
     *
     * @return Application
     */
    public function setRejectReasons($rejectReasons)
    {
        $this->rejectReasons = $rejectReasons;

        return $this;
    }

    /**
     * Get rejectReasons
     *
     * @return string
     */
    public function getRejectReasons()
    {
        return $this->rejectReasons;
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist
     *
     * @return Application
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
     * @return Application
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
     * Set closed
     *
     * @param boolean $closed
     *
     * @return Application
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * Get closed
     *
     * @return boolean
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * Set used
     *
     * @param boolean $used
     *
     * @return Application
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return boolean
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Set institution
     *
     * @param Institution $institution
     *
     * @return Application
     */
    public function setInstitution(Institution $institution = null)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution
     *
     * @return \DRI\AgreementBundle\Entity\Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set institutional
     *
     * @param Institutional $institutional
     *
     * @return Application
     */
    public function setInstitutional(Institutional $institutional = null)
    {
        $this->institutional = $institutional;

        return $this;
    }

    /**
     * Get institutional
     *
     * @return \DRI\AgreementBundle\Entity\Institutional
     */
    public function getInstitutional()
    {
        return $this->institutional;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
     *
     * @return Application
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
     * @return Application
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



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    AGREEMENT'S APPLICATION ADITIONALS METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/





    static function state_AcronimToName($state){
        switch ($state){
            case 'CON': return 'Confeccionada';break;
            case 'APR': return 'Aprobada';break;
            case 'REC': return 'Rechazada';break;
            default: return 'Estado de la Solicitud No Definido';break;
        }
    }

    static function state_NameToAcronim($state){
        switch ($state){
            case 'Confeccionada': return 'CON';break;
            case 'Aprobada': return 'APR';break;
            case 'Rechazada': return 'REC';break;
            default: return 'Estado de la Solicitud No Definido';break;
        }
    }

}
