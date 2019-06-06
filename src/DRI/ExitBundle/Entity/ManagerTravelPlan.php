<?php

namespace DRI\ExitBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DateTime;

use DRI\ClientBundle\Entity\Client;
use DRI\UserBundle\Entity\User;
use DRI\UsefulBundle\Entity\Country;
use DRI\UsefulBundle\Useful\Useful;

/**
 * ManagerTravelPlan
 *
 * @ORM\Table(name="ext_manager_travel_plan")
 * @ORM\Entity(repositoryClass="DRI\ExitBundle\Repository\ManagerTravelPlanRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Vich\Uploadable
 */
class ManagerTravelPlan
{

    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    MANAGER_TRAVEL_PLAN'S CONSTANTS
     * *
     * ********************************************************************************
     **********************************************************************************/

    public static $MANAGER_TRAVEL_PLAN_STATE = [
        'CON' =>'Confeccionada',
        'APR' =>'Aprobada',
        'REC' =>'Rechazada',
    ];

    public static $MANAGER_TRAVEL_PLAN_STATE_CHOICE = [
        'Confeccionada'  =>'CON',
        'Aprobada'       =>'APR',
        'Rechazada'      =>'REC',
    ];



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    MANAGER_TRAVEL_PLAN'S VARIABLES
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
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="DRI\ClientBundle\Entity\Client", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $client;

    /**
     * @ORM\ManyToMany(targetEntity="DRI\UsefulBundle\Entity\Country")
     * @ORM\JoinTable(name="ext_manager_travel_plans_countries",
     *      joinColumns={@ORM\JoinColumn(name="manager_travel_plan_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="country_id", referencedColumnName="id")}
     *      )
     */
    private $countries;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $objetives;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     */
    private $departureDate;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $lapsed;

    /**
     * One Application has many economis. This is the inverse side.
     * @ORM\OneToMany(targetEntity="DRI\ExitBundle\Entity\Economic", mappedBy="managerTravelPlan", cascade={"persist"})
     */
    private $financing;

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
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $approval;

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
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $reject;

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
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $canceled;

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
     * @ORM\OneToOne(targetEntity="DRI\ExitBundle\Entity\Application", mappedBy="managerTravelPlan")
     */
    private $application;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    MANAGER_TRAVEL_PLAN'S CONSTRUCTOR & TO_STRING METHOD
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * Application constructor.
     *
     */
    public function __construct()
    {
        $this->state     = 'CON';
        $this->financing = new ArrayCollection();
        $this->countries = new ArrayCollection();
        $this->createdAt = new DateTime('now');
        $this->updatedAt = new DateTime('now');
        $this->closed    = false;
        $this->used      = false;
        $this->canceled  = false;
    }

    public function __toString()
    {
        return $this->getNumber();
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    MANAGER_TRAVEL_PLAN'S ENTITY METHODS
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

    public function isCanceled(){
        if($this->canceled){
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
     * *    MANAGER_TRAVEL_PLAN'S GET & SET METHODS
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
     * @return ManagerTravelPlan
     */
    public function setNumber()
    {
        $aDate      = $this->departureDate->format('ymd');
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
     * @return ManagerTravelPlan
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
     * Set objetives
     *
     * @param string $objetives
     *
     * @return ManagerTravelPlan
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
     * Set departureDate
     *
     * @param DateTime $departureDate
     *
     * @return ManagerTravelPlan
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /**
     * Get departureDate
     *
     * @return DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Set lapsed
     *
     * @param string $lapsed
     *
     * @return ManagerTravelPlan
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
     * Set state
     *
     * @param string $state
     *
     * @return ManagerTravelPlan
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
     * Set approval
     *
     * @param boolean $approval
     *
     * @return ManagerTravelPlan
     */
    public function setApproval($approval)
    {
        $this->approval = $approval;

        return $this;
    }

    /**
     * Get approval
     *
     * @return boolean
     */
    public function getApproval()
    {
        return $this->approval;
    }

    /**
     * Set approvalDate
     *
     * @param DateTime $approvalDate
     *
     * @return ManagerTravelPlan
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
     * @return ManagerTravelPlan
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
     * Set reject
     *
     * @param boolean $reject
     *
     * @return ManagerTravelPlan
     */
    public function setReject($reject)
    {
        $this->reject = $reject;
        $this->setCanceled(true);

        return $this;
    }

    /**
     * Get reject
     *
     * @return boolean
     */
    public function getReject()
    {
        return $this->reject;
    }

    /**
     * Set rejectDate
     *
     * @param DateTime $rejectDate
     *
     * @return ManagerTravelPlan
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
     * @return ManagerTravelPlan
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
     * Set closed
     *
     * @param boolean $closed
     *
     * @return ManagerTravelPlan
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
     * @return ManagerTravelPlan
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
     * Set canceled
     *
     * @param boolean $canceled
     *
     * @return ManagerTravelPlan
     */
    public function setCanceled($canceled)
    {
        $this->canceled = $canceled;

        return $this;
    }

    /**
     * Get canceled
     *
     * @return boolean
     */
    public function getCanceled()
    {
        return $this->canceled;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     *
     * @return ManagerTravelPlan
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

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
     * @param DateTime $updatedAt
     *
     * @return ManagerTravelPlan
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

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
     * Set client
     *
     * @param Client $client
     *
     * @return ManagerTravelPlan
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
     * Add country
     *
     * @param Country $country
     *
     * @return ManagerTravelPlan
     */
    public function addCountry(Country $country)
    {
        if ($this->countries->contains($country)) {
            return $this;
        }
        $this->countries->add($country);
    }

    /**
     * Remove country
     *
     * @param Country $country
     */
    public function removeCountry(Country $country)
    {
        if (!$this->countries->contains($country)) {
            return;
        }

        $this->countries->removeElement($country);
    }

    /**
     * Get countries
     *
     * @return Collection
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * Add financing
     *
     * @param Economic $financing
     *
     */
    public function addFinancing(Economic $financing)
    {
        $financing->setManagerTravelPlan($this);

        $this->financing->add($financing);
    }

    /**
     * Remove financing
     *
     * @param Economic $financing
     */
    public function removeFinancing(Economic $financing)
    {
        $this->financing->removeElement($financing);
    }

    /**
     * Get financing
     *
     * @return ArrayCollection
     */
    public function getFinancing()
    {
        return $this->financing;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
     *
     * @return ManagerTravelPlan
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
     * @return ManagerTravelPlan
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
     * Set application
     *
     * @param Application $application
     *
     * @return ManagerTravelPlan
     */
    public function setApplication(Application $application = null)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    MANAGER_TRAVEL_PLAN'S ADITIONALS METHODS
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
