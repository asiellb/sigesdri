<?php

namespace DRI\ExitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DRI\ClientBundle\Entity\Client;
use DRI\UserBundle\Entity\User;
use DRI\PassportBundle\Entity\Passport;
use DRI\UsefulBundle\Entity\Country;
use DRI\ExitBundle\Entity\Economic;
use DRI\ExitBundle\Entity\Departure;

/**
 * ExitApplication
 *
 * @ORM\Table(name="exit_application")
 * @ORM\Entity(repositoryClass="DRI\ExitBundle\Repository\ExitApplicationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Vich\Uploadable
 */
class ExitApplication
{
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
     * @ORM\Column(name="noApp", type="string", unique=true)
     *
     */
    private $number;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="DRI\ClientBundle\Entity\Client", inversedBy="exitApplications", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $client;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Country", cascade={"persist"})
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="institution", type="string", length=100)
     *
     * @Assert\NotBlank()
     */
    private $institution;

    /**
     * @var string
     *
     * @ORM\Column(name="lapsed", type="string", length=100)
     *
     * @Assert\NotBlank()
     */
    private $lapsed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="exitDate", type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $exitDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="arrivalDate", type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $arrivalDate;

    /**
     * @var string
     *
     * @ORM\Column(name="concept", type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"ATE", "PPO", "ASE", "IAC", "EVE", "MOF", "BPR", "BPO", "PIN"})
     */
    private $concept;

    /**
     * @var string
     *
     * @ORM\Column(name="workPlanSynthesis", type="string", length=255, nullable=true)
     */
    private $workPlanSynthesis;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="exit_wps_files", fileNameProperty="workPlanSynthesis")
     */
    private $workPlanSynthesisFile;

    /**
     * @var string
     *
     * @ORM\Column(name="directiveSubstitute", type="string", length=100, nullable=true)
     */
    private $directiveSubstitute;

    /**
     * @var string
     *
     * @ORM\Column(name="goeSubstitute", type="string", length=100, nullable=true)
     */
    private $goeSubstitute;

    /**
     * @var string
     *
     * @ORM\Column(name="monthlyPay", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $monthlyPay;

    /**
     * @var string
     *
     * @ORM\Column(name="totalPay", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $totalPay;

    /**
     * @var array
     *
     * @ORM\Column(name="economics", type="array", nullable=true)
     */
    private $economics;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="DRI\ClientBundle\Entity\Client", cascade={"persist"})
     * @ORM\JoinColumn(name="proposed_client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $proposedBy;

    /**
     * @var bool
     *
     * @ORM\Column(name="pccApproval", type="boolean", nullable=true)
     */
    private $pccApproval;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pccApprovalDate", type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $pccApprovalDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="dcApproval", type="boolean", nullable=true)
     */
    private $dcApproval;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dcApprovalDate", type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $dcApprovalDate;

    /**
     * @var string
     *
     * @ORM\Column(name="agreement", type="string", length=4, nullable=true)
     */
    private $agreement;

    /**
     * @var bool
     *
     * @ORM\Column(name="ivApproval", type="boolean", nullable=true)
     */
    private $ivApproval;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ivApprovalDate", type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $ivApprovalDate;

    /**
     * @var bool
     *
     * @ORM\Column(name="secretOffice", type="boolean", nullable=true)
     */
    private $secretOffice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="secretOfficeDate", type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $secretOfficeDate;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"CON", "APR", "REC"})
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approvalDate", type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $approvalDate;

    /**
     * @var string
     *
     * @ORM\Column(name="approvalObservations", type="text", nullable=true)
     */
    private $approvalObservations;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rejectDate", type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $rejectDate;

    /**
     * @var string
     *
     * @ORM\Column(name="rejectReason", type="text", nullable=true)
     */
    private $rejectReason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     *
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
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
     * @ORM\OneToOne(targetEntity="DRI\ExitBundle\Entity\Departure", mappedBy="application")
     */
    private $departure;



    /**
     * ExitApplication constructor.
     *
     */
    public function __construct()
    {
        $this->state     = 'CON';
        $this->economics = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    public function __toString()
    {
        $name    = $this->getClient();
        $date    = $this->getCreatedAt();
        $country = $this->getCountry();

        return $country.' '.$name.' '.date($date);
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
     * Set number
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return ExitApplication
     */
    public function setNumber()
    {
        $aDate      = $this->exitDate->format('ymd');
        $aCountry   = $this->country->getIso3();
        $aClient    = substr($this->client->getFirstName(), 0, 1).''.substr($this->client->getFirstLastName(), 0, 1).''.$this->client->getId();

        $this->number = $aDate.''.$aCountry.'-'.$aClient;

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
     * Set institution
     *
     * @param string $institution
     *
     * @return ExitApplication
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
     * Set lapsed
     *
     * @param string $lapsed
     *
     * @return ExitApplication
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
     * @param \DateTime $exitDate
     *
     * @return ExitApplication
     */
    public function setExitDate($exitDate)
    {
        $this->exitDate = $exitDate;

        return $this;
    }

    /**
     * Get exitDate
     *
     * @return \DateTime
     */
    public function getExitDate()
    {
        return $this->exitDate;
    }

    /**
     * Set arrivalDate
     *
     * @param \DateTime $arrivalDate
     *
     * @return ExitApplication
     */
    public function setArrivalDate($arrivalDate)
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    /**
     * Get arrivalDate
     *
     * @return \DateTime
     */
    public function getArrivalDate()
    {
        return $this->arrivalDate;
    }

    /**
     * Set concept
     *
     * @param string $concept
     *
     * @return ExitApplication
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
     * Set workPlanSynthesis
     *
     * @param string $workPlanSynthesis
     *
     * @return ExitApplication
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
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $workPlanSynthesis
     *
     * @return ExitApplication
     */
    public function setWorkPlanSynthesisFile(File $workPlanSynthesis = null)
    {
        $this->workPlanSynthesisFile = $workPlanSynthesis;

        if ($workPlanSynthesis) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
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
     * Set directiveSubstitute
     *
     * @param string $directiveSubstitute
     *
     * @return ExitApplication
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
     * @return ExitApplication
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
     * Set monthlyPay
     *
     * @param string $monthlyPay
     *
     * @return ExitApplication
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
     * @return ExitApplication
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
     * Add economic
     *
     * @param Economic $economic
     *
     *
     */
    public function addEconomic(Economic $economic)
    {
        $this->economics->add($economic);
    }

    /**
     * Reveove economic
     *
     * @param Economic $economic
     *
     * @return $this
     */
    public function removeEconomic($economic)
    {
        if (false !== $key = array_search($economic, $this->economics, true)) {
            unset($this->economics[$key]);
            $this->economics = array_values($this->economics);
        }

        return $this;
    }

    /**
     * Get economics
     *
     * @return array
     */
    public function getEconomics()
    {
        return $this->economics;
    }

    /**
     * Set pccApproval
     *
     * @param boolean $pccApproval
     *
     * @return ExitApplication
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
     * @param \DateTime $pccApprovalDate
     *
     * @return ExitApplication
     */
    public function setPccApprovalDate($pccApprovalDate)
    {
        $this->pccApprovalDate = $pccApprovalDate;

        return $this;
    }

    /**
     * Get pccApprovalDate
     *
     * @return \DateTime
     */
    public function getPccApprovalDate()
    {
        return $this->pccApprovalDate;
    }

    /**
     * Set dcApproval
     *
     * @param boolean $dcApproval
     *
     * @return ExitApplication
     */
    public function setDcApproval($dcApproval)
    {
        $this->dcApproval = $dcApproval;

        return $this;
    }

    /**
     * Get dcApproval
     *
     * @return boolean
     */
    public function getDcApproval()
    {
        return $this->dcApproval;
    }

    /**
     * Set dcApprovalDate
     *
     * @param \DateTime $dcApprovalDate
     *
     * @return ExitApplication
     */
    public function setDcApprovalDate($dcApprovalDate)
    {
        $this->dcApprovalDate = $dcApprovalDate;

        return $this;
    }

    /**
     * Get dcApprovalDate
     *
     * @return \DateTime
     */
    public function getDcApprovalDate()
    {
        return $this->dcApprovalDate;
    }

    /**
     * Set agreement
     *
     * @param string $agreement
     *
     * @return ExitApplication
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
     * Set ivApproval
     *
     * @param boolean $ivApproval
     *
     * @return ExitApplication
     */
    public function setIvApproval($ivApproval)
    {
        $this->ivApproval = $ivApproval;

        return $this;
    }

    /**
     * Get ivApproval
     *
     * @return boolean
     */
    public function getIvApproval()
    {
        return $this->ivApproval;
    }

    /**
     * Set ivApprovalDate
     *
     * @param \DateTime $ivApprovalDate
     *
     * @return ExitApplication
     */
    public function setIvApprovalDate($ivApprovalDate)
    {
        $this->ivApprovalDate = $ivApprovalDate;

        return $this;
    }

    /**
     * Get ivApprovalDate
     *
     * @return \DateTime
     */
    public function getIvApprovalDate()
    {
        return $this->ivApprovalDate;
    }

    /**
     * Set secretOffice
     *
     * @param boolean $secretOffice
     *
     * @return ExitApplication
     */
    public function setSecretOffice($secretOffice)
    {
        $this->secretOffice = $secretOffice;

        return $this;
    }

    /**
     * Get secretOffice
     *
     * @return boolean
     */
    public function getSecretOffice()
    {
        return $this->secretOffice;
    }

    /**
     * Set secretOfficeDate
     *
     * @param \DateTime $secretOfficeDate
     *
     * @return ExitApplication
     */
    public function setSecretOfficeDate($secretOfficeDate)
    {
        $this->secretOfficeDate = $secretOfficeDate;

        return $this;
    }

    /**
     * Get secretOfficeDate
     *
     * @return \DateTime
     */
    public function getSecretOfficeDate()
    {
        return $this->secretOfficeDate;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return ExitApplication
     */
    public function setState($state)
    {
        $this->state = $state;

        switch ($state){
            case '2':
                $this->approvalDate = new \DateTime('now');
                break;
            case '3':
                $this->rejectDate = new \DateTime('now');
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
     * Set approvalDate
     *
     * @param \DateTime $approvalDate
     *
     * @return ExitApplication
     */
    public function setApprovalDate($approvalDate)
    {
        $this->approvalDate = $approvalDate;

        return $this;
    }

    /**
     * Get approvalDate
     *
     * @return \DateTime
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
     * @return ExitApplication
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
     * @param \DateTime $rejectDate
     *
     * @return ExitApplication
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
     * Set rejectReason
     *
     * @param string $rejectReason
     *
     * @return ExitApplication
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
     * Set createdAt
     *
     * @ORM\PrePersist()
     *
     * @return ExitApplication
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
     * @ORM\PreUpdate()
     *
     * @return ExitApplication
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
     * Set client
     *
     * @param Client $client
     *
     * @return ExitApplication
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
     * Set country
     *
     * @param Country $country
     *
     * @return ExitApplication
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
     * Set proposedBy
     *
     * @param Client $proposedBy
     *
     * @return ExitApplication
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
     * Set createdBy
     *
     * @param User $createdBy
     *
     * @return ExitApplication
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
     * @return ExitApplication
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
     * Set economics
     *
     * @param array $economics
     *
     * @return ExitApplication
     */
    public function setEconomics($economics)
    {
        $this->economics = $economics;

        return $this;
    }

    /**
     * Set departure
     *
     * @param Departure $departure
     *
     * @return ExitApplication
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
}
