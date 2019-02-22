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

/**
 * Application
 *
 * @ORM\Table(name="passport_application")
 * @ORM\Entity(repositoryClass="DRI\PassportBundle\Repository\ApplicationRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity("applicationNumber")
 *
 * @Vich\Uploadable
 */
class Application
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
     * @ORM\Column(name="applicationNumber", type="string")
     *
     */
    private $applicationNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="applicationReason", type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"CON", "PRO"})
     */
    private $applicationReason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="applicationDate", type="date")
     *
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $applicationDate;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="DRI\ClientBundle\Entity\Client", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="passportType", type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"COR", "DIP", "OFI", "SER", "MAR"})
     */
    private $passportType;

    /**
     * @var string
     *
     * @ORM\Column(name="applicationType", type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"REG", "INM"})
     */
    private $applicationType;

    /**
     * @var string
     *
     * @ORM\Column(name="applicantOrgan", type="string", length=100)
     *
     * @Assert\NotBlank()
     */
    private $applicantOrgan;

    /**
     * @var string
     *
     * @ORM\Column(name="travelReason", type="string", length=255)
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
     * @ORM\Column(name="sendDate", type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $sendDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="confirmDate", type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $confirmDate;

    /**
     * @var Passport
     *
     * @ORM\ManyToOne(targetEntity="DRI\PassportBundle\Entity\Passport", inversedBy="applications", cascade={"persist"})
     * @ORM\JoinColumn(name="passport_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $passport;

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
     * @ORM\Column(name="rejectReasons", type="string", length=255, nullable=true)
     */
    private $rejectReasons;

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
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
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
     * Application constructor.
     *
     */
    public function __construct()
    {
        $this->state     = 'CON';
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    public function __toString()
    {
        $appNumber = $this->getApplicationNumber();

        return $appNumber;
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
     * Set applicationReason
     *
     * @param string $applicationReason
     *
     * @return Application
     */
    public function setApplicationReason($applicationReason)
    {
        $this->applicationReason = $applicationReason;

        return $this;
    }

    /**
     * Get applicationReason
     *
     * @return string
     */
    public function getApplicationReason()
    {
        return $this->applicationReason;
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
     * Set applicantOrgan
     *
     * @param string $applicantOrgan
     *
     * @return Application
     */
    public function setApplicantOrgan($applicantOrgan)
    {
        $this->applicantOrgan = $applicantOrgan;

        return $this;
    }

    /**
     * Get applicantOrgan
     *
     * @return string
     */
    public function getApplicantOrgan()
    {
        return $this->applicantOrgan;
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
                break;
            case 'REC':
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
     * Get sendDate
     *
     * @return \DateTime
     */
    public function getSendDate()
    {
        return $this->sendDate;
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
     * Set Passport
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
     * Get Passport
     *
     * @return Passport
     */
    public function getPassport()
    {
        return $this->passport;
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
     * Set applicationNumber
     *
     * @ORM\PrePersist
     *
     * @return Application
     */
    public function setApplicationNumber()
    {
        $reason = $this->getApplicationReason();
        $date = date_format($this->getApplicationDate(), "ymd");
        $passp = $this->getPassportType();
        $client = $this->getClient()->getId();

        $this->applicationNumber = $reason.$date.$passp.$client;

        return $this;
    }

    /**
     * Get applicationNumber
     *
     * @return string
     */
    public function getApplicationNumber()
    {
        return $this->applicationNumber;
    }
}
