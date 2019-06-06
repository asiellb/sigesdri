<?php

namespace DRI\ExitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DateTime;
use Exception;

use DRI\ClientBundle\Entity\Client;
use DRI\UserBundle\Entity\User;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Application
 *
 * @ORM\Table(name="ext_application")
 * @ORM\Entity(repositoryClass="DRI\ExitBundle\Repository\ApplicationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Vich\Uploadable
 */
class Application
{

    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    EXIT'S APPLICATION CONSTANTS
     * *
     * ********************************************************************************
     **********************************************************************************/

    public static $EXIT_APPLICATION_STATE = [
        'CON' =>'Confeccionada',
        'APR' =>'Aprobada',
        'REC' =>'Rechazada',
    ];

    public static $EXIT_APPLICATION_STATE_CHOICE = [
        'Confeccionada'  =>'CON',
        'Aprobada'       =>'APR',
        'Rechazada'      =>'REC',
    ];



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    EXIT'S APPLICATION VARIABLES
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
     * @ORM\Column(name="number_slug",type="string", unique=true)
     *
     */
    private $numberSlug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"dir", "doc", "est", "nod", "cua"})
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $inPlan;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="DRI\ClientBundle\Entity\Client", inversedBy="exitApplications", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $client;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="DRI\ClientBundle\Entity\Client", cascade={"persist"})
     * @ORM\JoinColumn(name="proposed_client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $proposedBy;

    /**
     * One Application has many missions. This is the inverse side.
     * @ORM\OneToMany(targetEntity="DRI\ExitBundle\Entity\Mission", mappedBy="application", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $missions;

    /**
     * @var CommandFile
     *
     * @ORM\OneToOne(targetEntity="DRI\ExitBundle\Entity\CommandFile", cascade={"persist", "remove"})
     */
    private $commandFile;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\NotBlank()
     */
    private $lapsed;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $exitDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $arrivalDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $directiveSubstitute;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $goeSubstitute;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $pccApproval;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $pccApprovalDate;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $vriApproval;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $vriApprovalDate;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $rsApproval;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $rsApprovalDate;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $osApproval;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $osApprovalDate;

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
     * @var string
     *
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $agreement;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $approvalDate;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $approvalObservations;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $rejectDate;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $rejectReason;


    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $digitalCopy;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="ext_app_dco_files", fileNameProperty="digitalCopy")
     */
    private $digitalCopyFile;

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
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
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
     * @ORM\OneToOne(targetEntity="DRI\ExitBundle\Entity\ManagerTravelPlan", inversedBy="application")
     * @ORM\JoinColumn(name="manager_travel_plan_id", referencedColumnName="id")
     */
    private $managerTravelPlan;

    /**
     * @ORM\OneToOne(targetEntity="DRI\ExitBundle\Entity\Departure", mappedBy="application")
     */
    private $departure;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    EXIT'S APPLICATION CONSTRUCTOR & TO_STRING METHOD
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * Application constructor.
     *
     */
    public function __construct()
    {
        $this->missions  = new ArrayCollection();
        $this->state     = 'CON';
        $this->closed    = false;
        $this->used      = false;
        $this->inPlan    = false;
        $this->createdAt = new DateTime('now');
        $this->updatedAt = new DateTime('now');
    }

    public function __toString()
    {
        return $this->getNumber();
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    EXIT'S APPLICATION ENTITY METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/




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
     * *    EXIT'S APPLICATION GET & SET METHODS
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
     * @ORM\PreUpdate
     *
     * @return Application
     */
    public function setNumber()
    {
        $aDate      = $this->exitDate->format('ymd');
        $aClient    = substr($this->client->getFirstName(), 0, 1).''.substr($this->client->getFirstLastName(), 0, 1).''.$this->client->getId();

        $this->number = $aDate.'-'.$aClient;
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
     * Set proposedBy
     *
     * @param Client $proposedBy
     *
     * @return Application
     */
    public function setProposedBy(Client $proposedBy = null)
    {
        $this->proposedBy = $proposedBy;

        return $this;
    }

    /**
     * Get proposedBy
     *
     * @return Client
     */
    public function getProposedBy()
    {
        return $this->proposedBy;
    }

    /**
     * Set lapsed
     *
     * @param string $lapsed
     *
     * @return Application
     */
    public function setLapsed($lapsed)
    {
        $this->lapsed = $lapsed;

        return $this;
    }

    /**
     * Get lapsed
     *
     * @return string
     */
    public function getLapsed()
    {
        return $this->lapsed;
    }

    /**
     * Set exitDate
     *
     * @param DateTime $exitDate
     *
     * @return Application
     */
    public function setExitDate($exitDate)
    {
        $this->exitDate = $exitDate;

        return $this;
    }

    /**
     * Get exitDate
     *
     * @return DateTime
     */
    public function getExitDate()
    {
        return $this->exitDate;
    }

    /**
     * Set arrivalDate
     *
     * @param DateTime $arrivalDate
     *
     * @return Application
     */
    public function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    /**
     * Get arrivalDate
     *
     * @return DateTime
     */
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * Set directiveSubstitute
     *
     * @param string $directiveSubstitute
     *
     * @return Application
     */
    public function setDirectiveSubstitute($directiveSubstitute)
    {
        $this->directiveSubstitute = $directiveSubstitute;

        return $this;
    }

    /**
     * Get directiveSubstitute
     *
     * @return string
     */
    public function getDirectiveSubstitute()
    {
        return $this->directiveSubstitute;
    }

    /**
     * Set goeSubstitute
     *
     * @param string $goeSubstitute
     *
     * @return Application
     */
    public function setGoeSubstitute($goeSubstitute)
    {
        $this->goeSubstitute = $goeSubstitute;

        return $this;
    }

    /**
     * Get goeSubstitute
     *
     * @return string
     */
    public function getGoeSubstitute()
    {
        return $this->goeSubstitute;
    }

    /**
     * Set pccApproval
     *
     * @param boolean $pccApproval
     *
     * @return Application
     */
    public function setPccApproval($pccApproval)
    {
        $this->pccApproval = $pccApproval;

        return $this;
    }

    /**
     * Get pccApproval
     *
     * @return boolean
     */
    public function getPccApproval()
    {
        return $this->pccApproval;
    }

    /**
     * Set pccApprovalDate
     *
     * @param DateTime $pccApprovalDate
     *
     * @return Application
     */
    public function setPccApprovalDate($pccApprovalDate)
    {
        $this->pccApprovalDate = $pccApprovalDate;

        return $this;
    }

    /**
     * Get pccApprovalDate
     *
     * @return DateTime
     */
    public function getPccApprovalDate()
    {
        return $this->pccApprovalDate;
    }

    /**
     * Set vriApproval
     *
     * @param boolean $vriApproval
     *
     * @return Application
     */
    public function setVriApproval($vriApproval)
    {
        $this->vriApproval = $vriApproval;

        return $this;
    }

    /**
     * Get vriApproval
     *
     * @return boolean
     */
    public function getVriApproval()
    {
        return $this->vriApproval;
    }

    /**
     * Set vriApprovalDate
     *
     * @param DateTime $vriApprovalDate
     *
     * @return Application
     */
    public function setVriApprovalDate($vriApprovalDate)
    {
        $this->vriApprovalDate = $vriApprovalDate;

        return $this;
    }

    /**
     * Get vriApprovalDate
     *
     * @return DateTime
     */
    public function getVriApprovalDate()
    {
        return $this->vriApprovalDate;
    }

    /**
     * Set rsApproval
     *
     * @param boolean $rsApproval
     *
     * @return Application
     */
    public function setRsApproval($rsApproval)
    {
        $this->rsApproval = $rsApproval;

        return $this;
    }

    /**
     * Get rsApproval
     *
     * @return boolean
     */
    public function getRsApproval()
    {
        return $this->rsApproval;
    }

    /**
     * Set rsApprovalDate
     *
     * @param DateTime $rsApprovalDate
     *
     * @return Application
     */
    public function setRsApprovalDate($rsApprovalDate)
    {
        $this->rsApprovalDate = $rsApprovalDate;

        return $this;
    }

    /**
     * Get rsApprovalDate
     *
     * @return DateTime
     */
    public function getRsApprovalDate()
    {
        return $this->rsApprovalDate;
    }

    /**
     * Set osApproval
     *
     * @param boolean $osApproval
     *
     * @return Application
     */
    public function setOsApproval($osApproval)
    {
        $this->osApproval = $osApproval;

        return $this;
    }

    /**
     * Get osApproval
     *
     * @return boolean
     */
    public function getOsApproval()
    {
        return $this->osApproval;
    }

    /**
     * Set osApprovalDate
     *
     * @param DateTime $osApprovalDate
     *
     * @return Application
     */
    public function setOsApprovalDate($osApprovalDate)
    {
        $this->osApprovalDate = $osApprovalDate;

        return $this;
    }

    /**
     * Get osApprovalDate
     *
     * @return DateTime
     */
    public function getOsApprovalDate()
    {
        return $this->osApprovalDate;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Application
     * @throws Exception
     */
    public function setState($state)
    {
        $this->state = $state;

        switch ($state){
            case 'APR':
                $this->approvalDate = new DateTime('now');
                $this->closed = true;
                break;
            case 'REC':
                $this->rejectDate = new DateTime('now');
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
     * Set agreement
     *
     * @param string $agreement
     *
     * @return Application
     */
    public function setAgreement($agreement)
    {
        $this->agreement = $agreement;

        return $this;
    }

    /**
     * Get agreement
     *
     * @return string
     */
    public function getAgreement()
    {
        return $this->agreement;
    }

    /**
     * Set approvalDate
     *
     * @param DateTime $approvalDate
     *
     * @return Application
     */
    public function setApprovalDate($approvalDate)
    {
        $this->approvalDate = $approvalDate;

        return $this;
    }

    /**
     * Get approvalDate
     *
     * @return DateTime
     */
    public function getApprovalDate()
    {
        return $this->approvalDate;
    }

    /**
     * Set approvalObservations
     *
     * @param string $approvalObservations
     *
     * @return Application
     */
    public function setApprovalObservations($approvalObservations)
    {
        $this->approvalObservations = $approvalObservations;

        return $this;
    }

    /**
     * Get approvalObservations
     *
     * @return string
     */
    public function getApprovalObservations()
    {
        return $this->approvalObservations;
    }

    /**
     * Set rejectDate
     *
     * @param DateTime $rejectDate
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
     * @return DateTime
     */
    public function getRejectDate()
    {
        return $this->rejectDate;
    }

    /**
     * Set rejectReason
     *
     * @param string $rejectReason
     *
     * @return Application
     */
    public function setRejectReason($rejectReason)
    {
        $this->rejectReason = $rejectReason;

        return $this;
    }

    /**
     * Get rejectReason
     *
     * @return string
     */
    public function getRejectReason()
    {
        return $this->rejectReason;
    }

    /**
     * Set close
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
     * Set digitalCopy
     *
     * @param string $digitalCopy
     *
     * @return Application
     */
    public function setDigitalCopy($digitalCopy)
    {
        $this->digitalCopy = $digitalCopy;

        return $this;
    }

    /**
     * Get digitalCopy
     *
     * @return string
     */
    public function getDigitalCopy()
    {
        return $this->digitalCopy;
    }

    /**
     * @param File|UploadedFile $digitalCopy
     * @return Application
     * @throws Exception
     */
    public function setDigitalCopyFile(File $digitalCopy = null)
    {
        $this->digitalCopyFile = $digitalCopy;

        if ($digitalCopy) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getDigitalCopyFile()
    {
        return $this->digitalCopyFile;
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist()
     *
     * @return Application
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
     * @ORM\PreUpdate()
     *
     * @return Application
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
     * Set departure
     *
     * @param Departure $departure
     *
     * @return Application
     */
    public function setDeparture(Departure $departure = null)
    {
        $this->departure = $departure;

        return $this;
    }

    /**
     * Get departure
     *
     * @return Departure
     */
    public function getDeparture()
    {
        return $this->departure;
    }

    /**
     * Set managerTravelPlan
     *
     * @param ManagerTravelPlan $managerTravelPlan
     *
     * @return Application
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
     * Add mission
     *
     * @param Mission $mission
     *
     * @return Application
     */
    public function addMission(Mission $mission)
    {
        $mission->setApplication($this);
        $this->missions[] = $mission;

        return $this;
    }

    /**
     * Remove mission
     *
     * @param Mission $mission
     */
    public function removeMission(Mission $mission)
    {
        $this->missions->removeElement($mission);
    }

    /**
     * Get missions
     *
     * @return Collection
     */
    public function getMissions()
    {
        return $this->missions;
    }

    /**
     * Set commandFile
     *
     * @param CommandFile $commandFile
     *
     * @return Application
     */
    public function setCommandFile(CommandFile $commandFile = null)
    {
        $this->commandFile = $commandFile;

        return $this;
    }

    /**
     * Get commandFile
     *
     * @return CommandFile
     */
    public function getCommandFile()
    {
        return $this->commandFile;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Application
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
     * Set inPlan
     *
     * @param boolean $inPlan
     *
     * @return Application
     */
    public function setInPlan($inPlan)
    {
        $this->inPlan = $inPlan;

        return $this;
    }

    /**
     * Get inPlan
     *
     * @return boolean
     */
    public function getInPlan()
    {
        return $this->inPlan;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    EXIT'S APPLICATION ADITIONALS METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * @param $state
     * @return string
     */
    static function state_AcronimToName($state){
        switch ($state){
            case 'CON': return 'Confeccionada';break;
            case 'APR': return 'Aprobada';break;
            case 'REC': return 'Rechazada';break;
            default: return 'Estado de la Solicitud No Definido';break;
        }
    }

    /**
     * @param $state
     * @return string
     */
    static function state_NameToAcronim($state){
        switch ($state){
            case 'Confeccionada': return 'CON';break;
            case 'Aprobada': return 'APR';break;
            case 'Rechazada': return 'REC';break;
            default: return 'Estado de la Solicitud No Definido';break;
        }
    }

}
