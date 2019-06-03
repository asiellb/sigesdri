<?php

namespace DRI\PassportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DRI\ClientBundle\Entity\Client;
use DRI\UserBundle\Entity\User;
use DRI\PassportBundle\Entity\Passport;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Passport Application
 *
 * @ORM\Table(name="pas_application")
 * @ORM\Entity(repositoryClass="DRI\PassportBundle\Repository\ApplicationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity("number")
 *
 * @Vich\Uploadable
 */
class Application
{

    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S APPLICATION CONSTANTS
     * *
     * ********************************************************************************
     **********************************************************************************/

    const PASSPORT_APPLICATION_REASON = [
        'CON' => 'Confección',
        'PRO' => 'Prórroga',
    ];

    const PASSPORT_APPLICATION_REASON_CHOICE = [
        'Confección' =>'CON',
        'Prórroga'   =>'PRO'
    ];

    const PASSPORT_APPLICATION_TYPE = [
        'REG' => 'Regular',
        'INM' => 'Inmediato',
    ];

    const PASSPORT_APPLICATION_TYPE_CHOICE = [
        'Regular'   =>'REG',
        'Inmediato' =>'INM'
    ];

    const PASSPORT_APPLICATION_STATE = [
        'CON' => 'Confeccionada',
        'ENV' => 'Enviada',
        'CNF' => 'Confirmada',
        'REC' => 'Rechazada',
    ];

    const PASSPORT_APPLICATION_STATE_CHOICE = [
        'Confeccionada' => 'CON',
        'Enviada'       => 'ENV',
        'Confirmada'    => 'CNF',
        'Rechazada'     => 'REC'
    ];




    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S APPLICATION VARIABLES
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
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="DRI\ClientBundle\Entity\Client", inversedBy="passportApplications", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"CON", "PRO"})
     */
    private $reason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $applicationDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"COR", "DIP", "OFI", "SER", "MAR"})
     */
    private $passportType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"REG", "INM"})
     */
    private $applicationType;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\NotBlank()
     */
    private $organ;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $travelReason;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"CON", "ENV", "CNF", "REC"})
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $sendDate;

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
     * @ORM\JoinColumn(name="create_user_id", referencedColumnName="id", onDelete="SET NULL")
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
     * @ORM\OneToOne(targetEntity="DRI\PassportBundle\Entity\Passport", mappedBy="application")
     */
    private $passport;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S APPLICATION CONSTRUCTOR & TO_STRING METHOD
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * Application constructor.
     *
     */
    public function __construct()
    {
        $this->reason    = 'CON';
        $this->applicationType      = 'REG';
        $this->passportType         = 'OFI';
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
     * *    PASSPORT'S APPLICATION ENTITY METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/




    public function isConfirmed(){
        if($this->getState() == 'CON'){
            return true;
        }
        return false;
    }

    public function isSented(){
        if($this->getState() == 'ENV'){
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

    public function hasClient(){
        if(is_null($this->client)){
            return null;
        }
        return true;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S APPLICATION GET & SET METHODS
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
        $reason = $this->getReason();
        $date = date_format($this->getApplicationDate(), "ymd");
        $passp = $this->getPassportType();
        $client = $this->getClient()->getId();

        $this->number = $reason.$date.$passp.$client;
        $this->numberSlug = Useful::getSlug($this->number);

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
     * Set client
     *
     * @param Client $client
     *
     * @return Application
     */
    public function setClient(Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set reason
     *
     * @param string $reason
     *
     * @return Application
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set applicationDate
     *
     * @param \DateTime $applicationDate
     *
     * @return Application
     */
    public function setApplicationDate($applicationDate)
    {
        $this->applicationDate = $applicationDate;

        return $this;
    }

    /**
     * Get applicationDate
     *
     * @return \DateTime
     */
    public function getApplicationDate()
    {
        return $this->applicationDate;
    }

    /**
     * Set passportType
     *
     * @param string $passportType
     *
     * @return Application
     */
    public function setPassportType($passportType)
    {
        $this->passportType = $passportType;

        return $this;
    }

    /**
     * Get passportType
     *
     * @return string
     */
    public function getPassportType()
    {
        return $this->passportType;
    }

    /**
     * Set applicationType
     *
     * @param string $applicationType
     *
     * @return Application
     */
    public function setApplicationType($applicationType)
    {
        $this->applicationType = $applicationType;

        return $this;
    }

    /**
     * Get applicationType
     *
     * @return string
     */
    public function getApplicationType()
    {
        return $this->applicationType;
    }

    /**
     * Set organ
     *
     * @param string $organ
     *
     * @return Application
     */
    public function setOrgan($organ)
    {
        $this->organ = $organ;

        return $this;
    }

    /**
     * Get organ
     *
     * @return string
     */
    public function getOrgan()
    {
        return $this->organ;
    }

    /**
     * Set travelReason
     *
     * @param string $travelReason
     *
     * @return Application
     */
    public function setTravelReason($travelReason)
    {
        $this->travelReason = $travelReason;

        return $this;
    }

    /**
     * Get travelReason
     *
     * @return string
     */
    public function getTravelReason()
    {
        return $this->travelReason;
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

        switch ($state){
            case 'ENV':
                $this->sendDate = new \DateTime('now');
                break;
            case 'CNF':
                $this->confirmDate = new \DateTime('now');
                $this->closed = true;
                break;
            case 'REC':
                $this->rejectDate = new \DateTime('now');
                $this->closed = true;
                break;
            default:
                break;
        }

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
     * Set sendDate
     *
     * @param \DateTime $sendDate
     *
     * @return Application
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    /**
     * Get sendDate
     *
     * @return \DateTime
     */
    public function getSendDate()
    {
        return $this->sendDate;
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
     * @return User
     */
    public function getLastUpdateBy()
    {
        return $this->lastUpdateBy;
    }

    /**
     * Set passport
     *
     * @param Passport $passport
     *
     * @return Application
     */
    public function setPassport(Passport $passport = null)
    {
        $this->passport = $passport;

        return $this;
    }

    /**
     * Get departure
     *
     * @return Passport
     */
    public function getPassport()
    {
        return $this->passport;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S ADITIONALS METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/




    static function reason_AcronimToName($reason){
        switch ($reason){
            case 'CON': return 'Confección';break;
            case 'PRO': return 'Prórroga';break;
            default: return 'Razón de la Solicitud No Definido';break;
        }
    }

    static function reason_NameToAcronim($reason){
        switch ($reason){
            case 'Confección': return 'CON';break;
            case 'Prórroga': return 'PRO';break;
            default: return 'Razón de la Solicitud No Definido';break;
        }
    }

    static function type_AcronimToName($type){
        switch ($type){
            case 'REG': return 'Regular';break;
            case 'INM': return 'Inmediato';break;
            default: return 'Tipo de Solicitud No Definido';break;
        }
    }

    static function type_NameToAcronim($type){
        switch ($type){
            case 'Regular': return 'REG';break;
            case 'Inmediato': return 'INM';break;
            default: return 'Tipo de Solicitud No Definido';break;
        }
    }

    static function state_AcronimToName($state){
        switch ($state){
            case 'CON': return 'Confeccionada';break;
            case 'ENV': return 'Enviada';break;
            case 'CNF': return 'Confirmada';break;
            case 'REC': return 'Rechazada';break;
            default: return 'Tipo de la Solicitud No Definido';break;
        }
    }

    static function state_NameToAcronim($state){
        switch ($state){
            case 'Confeccionada': return 'CON';break;
            case 'Enviada': return 'ENV';break;
            case 'Confirmada': return 'CNF';break;
            case 'Rechazada': return 'REC';break;
            default: return 'Tipo de Solicitud No Definido';break;
        }
    }

}
