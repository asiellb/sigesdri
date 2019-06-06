<?php

namespace DRI\ExitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DateTime;
use Exception;

use DRI\UserBundle\Entity\User;
use DRI\UsefulBundle\Entity\Country;

/**
 * Mission
 *
 * @ORM\Table(name="ext_mission")
 * @ORM\Entity(repositoryClass="DRI\ExitBundle\Repository\MissionRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Vich\Uploadable
 */
class Mission
{

    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    MISSION'S CONSTANTS
     * *
     * ********************************************************************************
     **********************************************************************************/

    public static $MISSION_CONCEPT = [
        'MOF' =>'Misión Oficial',
        'IAC' =>'Intercambio Académico',
        'IES' =>'Intercambio Estudiantil',
        'ICU' =>'Intercambio Cultural',
        'EVE' =>'Evento',
        'BMA' =>'Beca Maestría',
        'BPR' =>'Beca Predoctoral',
        'BPO' =>'Beca Postdoctoral',
        'PIN' =>'Proyecto Internacional',
        'ATE' =>'Asistencia Técnica Exportada',
        'COM' =>'Comercialización',
    ];

    public static $MISSION_CONCEPT_CHOICE = [
        'Misión Oficial'                =>'MOF',
        'Intercambio Académico'         =>'IAC',
        'Intercambio Estudiantil'       =>'IES',
        'Intercambio Cultural'          =>'ICU',
        'Evento'                        =>'EVE',
        'Beca Maestría'                 =>'BMA',
        'Beca Predoctoral'              =>'BPR',
        'Beca Postdoctoral'             =>'BPO',
        'Proyecto Internacional'        =>'PIN',
        'Asistencia Técnica Exportada'  =>'ATE',
        'Comercialización'              =>'COM',
    ];



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    MISSION'S VARIABLES
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
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Country")
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $provinceCountry;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $institution;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $personWhoInvitesName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $personWhoInvitesPosition;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $fromDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $untilDate;

    /**
     * @var string
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank()
     */
    private $timeAmount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={ "MOF", "IAC", "IES", "ICU", "EVE", "BMA", "BPR", "BPO", "PIN", "ATE", "COM"})
     */
    private $concept;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     */
    private $objetives;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $workPlanSynthesis;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="ext_mis_wps_files", fileNameProperty="workPlanSynthesis")
     */
    private $workPlanSynthesisFile;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $monthlyPay;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $totalPay;

    /**
     * One Mission has many economis. This is the inverse side.
     * @ORM\OneToMany(targetEntity="DRI\ExitBundle\Entity\Economic", mappedBy="mission", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $economics;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $approved;

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
     * Many missions have one Application. This is the owning side.
     *
     * @ORM\ManyToOne(targetEntity="DRI\ExitBundle\Entity\Application", inversedBy="missions")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    private $application;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    MISSION'S CONSTRUCTOR & TO_STRING METHOD
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->approved = true;
        $this->economics = new ArrayCollection();
        $this->createdAt = new DateTime('now');
        $this->updatedAt = new DateTime('now');
    }

    public function isApproved(){
        if($this->approved){
            return true;
        }
        return false;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    MISSION'S GET & SET METHODS
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
     * Set provinceCountry
     *
     * @param string $provinceCountry
     *
     * @return Mission
     */
    public function setProvinceCountry($provinceCountry)
    {
        $this->provinceCountry = $provinceCountry;

        return $this;
    }

    /**
     * Get provinceCountry
     *
     * @return string
     */
    public function getProvinceCountry()
    {
        return $this->provinceCountry;
    }

    /**
     * Set institution
     *
     * @param string $institution
     *
     * @return Mission
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;

        return $this;
    }

    /**
     * Get institution
     *
     * @return string
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Set personWhoInvitesName
     *
     * @param string $personWhoInvitesName
     *
     * @return Mission
     */
    public function setPersonWhoInvitesName($personWhoInvitesName)
    {
        $this->personWhoInvitesName = $personWhoInvitesName;

        return $this;
    }

    /**
     * Get personWhoInvitesName
     *
     * @return string
     */
    public function getPersonWhoInvitesName()
    {
        return $this->personWhoInvitesName;
    }

    /**
     * Set personWhoInvitesPosition
     *
     * @param string $personWhoInvitesPosition
     *
     * @return Mission
     */
    public function setPersonWhoInvitesPosition($personWhoInvitesPosition)
    {
        $this->personWhoInvitesPosition = $personWhoInvitesPosition;

        return $this;
    }

    /**
     * Get personWhoInvitesPosition
     *
     * @return string
     */
    public function getPersonWhoInvitesPosition()
    {
        return $this->personWhoInvitesPosition;
    }

    /**
     * Set fromDate
     *
     * @param DateTime $fromDate
     *
     * @return Mission
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Get fromDate
     *
     * @return DateTime
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * Set untilDate
     *
     * @param DateTime $untilDate
     *
     * @return Mission
     */
    public function setUntilDate($untilDate)
    {
        $this->untilDate = $untilDate;

        return $this;
    }

    /**
     * Get untilDate
     *
     * @return DateTime
     */
    public function getUntilDate()
    {
        return $this->untilDate;
    }

    /**
     * Set timeAmount
     *
     * @param integer $timeAmount
     *
     * @return Mission
     */
    public function setTimeAmount($timeAmount)
    {
        $this->timeAmount = $timeAmount;

        return $this;
    }

    /**
     * Get timeAmount
     *
     * @return integer
     */
    public function getTimeAmount()
    {
        return $this->timeAmount;
    }

    /**
     * Set concept
     *
     * @param string $concept
     *
     * @return Mission
     */
    public function setConcept($concept)
    {
        $this->concept = $concept;

        return $this;
    }

    /**
     * Get concept
     *
     * @return string
     */
    public function getConcept()
    {
        return $this->concept;
    }

    /**
     * Set objetives
     *
     * @param string $objetives
     *
     * @return Mission
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
     * Set workPlanSynthesis
     *
     * @param string $workPlanSynthesis
     *
     * @return Mission
     */
    public function setWorkPlanSynthesis($workPlanSynthesis)
    {
        $this->workPlanSynthesis = $workPlanSynthesis;

        return $this;
    }

    /**
     * Get workPlanSynthesis
     *
     * @return string
     */
    public function getWorkPlanSynthesis()
    {
        return $this->workPlanSynthesis;
    }

    /**
     * @param File|UploadedFile $workPlanSynthesis
     * @return Mission
     * @throws Exception
     */
    public function setWorkPlanSynthesisFile(File $workPlanSynthesis = null)
    {
        $this->workPlanSynthesisFile = $workPlanSynthesis;

        if ($workPlanSynthesis) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getWorkPlanSynthesisFile()
    {
        return $this->workPlanSynthesisFile;
    }

    /**
     * Set monthlyPay
     *
     * @param string $monthlyPay
     *
     * @return Mission
     */
    public function setMonthlyPay($monthlyPay)
    {
        $this->monthlyPay = $monthlyPay;

        return $this;
    }

    /**
     * Get monthlyPay
     *
     * @return string
     */
    public function getMonthlyPay()
    {
        return $this->monthlyPay;
    }

    /**
     * Set totalPay
     *
     * @param string $totalPay
     *
     * @return Mission
     */
    public function setTotalPay($totalPay)
    {
        $this->totalPay = $totalPay;

        return $this;
    }

    /**
     * Get totalPay
     *
     * @return string
     */
    public function getTotalPay()
    {
        return $this->totalPay;
    }

    /**
     * Set country
     *
     * @param Country $country
     *
     * @return Mission
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
     * Add economic
     *
     * @param Economic $economic
     */
    public function addEconomic(Economic $economic)
    {
        $economic->setMission($this);

        $this->economics->add($economic);
    }

    /**
     * Reveove economic
     *
     * @param Economic $economic
     *
     * @return $this
     */
    public function removeEconomic(Economic $economic)
    {
        $this->economics->removeElement($economic);

        return $this;
    }

    /**
     * Get economics
     *
     * @return ArrayCollection
     */
    public function getEconomics()
    {
        return $this->economics;
    }

    /**
     * Set a
     *
     * @param boolean $approved
     *
     * @return Mission
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist()
     *
     * @return Mission
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
     * @return Mission
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
     * @return Mission
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
     * @return Mission
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
     * @return Mission
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
     * *    MISSION'S ADITIONALS METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * @param $concept
     * @return string
     */
    static function concept_AcronimToName($concept){
        switch ($concept){
            case 'MOF': return 'Misión Oficial';break;
            case 'IAC': return 'Intercambio Académico';break;
            case 'IES': return 'Intercambio Estudiantil';break;
            case 'ICU': return 'Intercambio Cultural';break;
            case 'EVE': return 'Evento';break;
            case 'BMA': return 'Beca Maestría';break;
            case 'BPR': return 'Beca Predoctoral';break;
            case 'BPO': return 'Beca Postdoctoral';break;
            case 'PIN': return 'Proyecto Internacional';break;
            case 'ATE': return 'Asistencia Técnica Exportada';break;
            case 'COM': return 'Comercialización';break;
            default: return 'Concepto de la Misión No Definido';break;
        }
    }

    /**
     * @param $concept
     * @return string
     */
    static function concept_NameToAcronim($concept){
        switch ($concept){
            case 'Misión Oficial': return 'MOF';break;
            case 'Intercambio Académico': return 'IAC';break;
            case 'Intercambio Estudiantil': return 'IES';break;
            case 'Intercambio Cultural': return 'ICU';break;
            case 'Evento': return 'EVE';break;
            case 'Beca Maestría': return 'BMA';break;
            case 'Beca Predoctoral': return 'BPR';break;
            case 'Beca Postdoctoral': return 'BPO';break;
            case 'Proyecto Internacional': return 'PIN';break;
            case 'Asistencia Técnica Exportada': return 'ATE';break;
            case 'Comercialización': return 'COM';break;
            default: return 'Concepto de la Misión No Definido';break;
        }
    }

}
