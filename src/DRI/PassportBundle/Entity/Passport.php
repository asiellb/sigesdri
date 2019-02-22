<?php

namespace DRI\PassportBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DRI\ClientBundle\Entity\Client;
use DRI\UserBundle\Entity\User;
use DRI\PassportBundle\Entity\Application;

/**
 * Passport
 *
 * @ORM\Table(name="passport")
 * @ORM\Entity(repositoryClass="DRI\PassportBundle\Repository\PassportRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("number")
 *
 * @Vich\Uploadable
 */
class Passport
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DRI\PassportBundle\Entity\Application", mappedBy="passport")
     */
    private $applications;

    /**
     * @var string
     *
     * @ORM\Column(name="noPas", type="string", length=7, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=7, max=7)
     */
    private $number;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="DRI\ClientBundle\Entity\Client", inversedBy="passports", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $holder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="issueDate", type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $issueDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiryDate", type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $expiryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"ORD", "OFI"})
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"ACT", "VEN", "BAJ"})
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstPage;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="passports_images", fileNameProperty="firstPage")
     */
    private $firstPageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="dropReason", type="string", length=255, nullable=true)
     */
    private $dropReason;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dropDate", type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $dropDate;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DRI\ExitBundle\Entity\Departure", mappedBy="passport")
     */
    private $departures;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     *
     * @Assert\Date()
     */
    private $createdAt;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     *
     * @Assert\Date()
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
     * @var string
     *
     * @ORM\Column(name="client_ci", type="string", length=11)
     */
    private $clientCi;



    /**
     * Passport constructor.
     *
     */
    public function __construct()
    {
        $this->firstPage = '';
        $this->dropDate = null;
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    public function __toString()
    {
        return $this->getNumber();
    }

    public function hasHolder(){
        if(is_null($this->holder)){
            return null;
        }
        return true;
    }


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
     * Set firstPage
     *
     * @param string $firstPage
     *
     * @return Passport
     */
    public function setFirstPage($firstPage)
    {
        $this->firstPage = $firstPage;

        return $this;
    }

    /**
     * Get firstPage
     *
     * @return string
     */
    public function getFirstPage()
    {
        return $this->firstPage;
    }


    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $firstPage
     *
     * @return Passport
     */
    public function setFirstPageFile(File $firstPage = null)
    {
        $this->firstPageFile = $firstPage;

        if ($firstPage) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFirstPageFile()
    {
        return $this->firstPageFile;
    }


    /**
     * Set number
     *
     * @param string $number
     *
     * @return Passport
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
     * Set issueDate
     *
     * @param \DateTime $issueDate
     *
     * @return Passport
     */
    public function setIssueDate($issueDate)
    {
        $this->issueDate = $issueDate;

        return $this;
    }

    /**
     * Get issueDate
     *
     * @return \DateTime
     */
    public function getIssueDate()
    {
        return $this->issueDate;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return Passport
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
     * Set type
     *
     * @param string $type
     *
     * @return Passport
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
     * Set state
     *
     * @param string $state
     *
     * @return Passport
     */
    public function setState($state)
    {
        $this->state = $state;

        if ($state == 'BAJ'){
            $this->dropDate = new \DateTime('now');
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
     * Set dropReason
     *
     * @param string $dropReason
     *
     * @return Passport
     */
    public function setDropReason($dropReason)
    {
        $this->dropReason = $dropReason;

        return $this;
    }

    /**
     * Get dropReason
     *
     * @return string
     */
    public function getDropReason()
    {
        return $this->dropReason;
    }

    /**
     * Get dropDate
     *
     * @return \DateTime
     */
    public function getDropDate()
    {
        return $this->dropDate;
    }

    /**
     * Set exits
     *
     * @param array $exits
     *
     * @return Passport
     */
    public function setExits($exits)
    {
        $this->exits = $exits;

        return $this;
    }

    /**
     * Get exits
     *
     * @return array
     */
    public function getExits()
    {
        return $this->exits;
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist
     *
     * @return Passport
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime('now');;

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
     * @return Passport
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
     * Set holder
     *
     * @param Client $holder
     *
     * @return Passport
     */
    public function setHolder(Client $holder = null)
    {
        $this->holder = $holder;

        return $this;
    }

    /**
     * Get holder
     *
     * @return \DRI\ClientBundle\Entity\Client
     */
    public function getHolder()
    {
        return $this->holder;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
     *
     * @return Passport
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
     * Set dropDate
     *
     * @param \DateTime $dropDate
     *
     * @return Passport
     */
    public function setDropDate($dropDate)
    {
        $this->dropDate = $dropDate;

        return $this;
    }

    /**
     * Add application
     *
     * @param Application $application
     *
     * @return Passport
     */
    public function addApplication(Application $application)
    {
        $this->applications[] = $application;

        return $this;
    }

    /**
     * Remove application
     *
     * @param Application $application
     */
    public function removeApplication(Application $application)
    {
        $this->applications->removeElement($application);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Set lastUpdateBy
     *
     * @param User $lastUpdateBy
     *
     *
     *
     * @return Passport
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
     * Set clientCi
     *
     * @param string $clientCi
     *
     * @return Passport
     */
    public function setClientCi($clientCi)
    {
        $this->clientCi = $clientCi;

        return $this;
    }

    /**
     * Get clientCi
     *
     * @return string
     */
    public function getClientCi()
    {
        return $this->clientCi;
    }

    /**
     * Add departure
     *
     * @param \DRI\ExitBundle\Entity\Departure $departure
     *
     * @return Passport
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
