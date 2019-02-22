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

/**
 * Departure
 *
 * @ORM\Table(name="departure")
 * @ORM\Entity(repositoryClass="DRI\ExitBundle\Repository\DepartureRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Vich\Uploadable
 */
class Departure
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
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="DRI\ClientBundle\Entity\Client", inversedBy="departures", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $client;

    /**
     * @var Passport
     *
     * @ORM\ManyToOne(targetEntity="DRI\PassportBundle\Entity\Passport", inversedBy="departures", cascade={"persist"})
     * @ORM\JoinColumn(name="passport_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $passport;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="passport_delivery", type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $passportDelivery;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="departure_date", type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $departureDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="return_date", type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $returnDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="passport_collection", type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $passportCollection;

    /**
     * @ORM\OneToOne(targetEntity="DRI\ExitBundle\Entity\ExitApplication", inversedBy="departure")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    private $application;

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="string", length=255, nullable=true)
     */
    private $observations;

    /**
     * @var string
     *
     * @ORM\Column(name="results", type="string", length=255, nullable=true)
     */
    private $results;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="departure_results_files", fileNameProperty="results")
     */
    private $resultsFile;

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
     * Departure constructor.
     *
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }


    public function hasClient(){
        if(is_null($this->client)){
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
     * Set passportDelivery
     *
     * @param \DateTime $passportDelivery
     *
     * @return Departure
     */
    public function setPassportDelivery($passportDelivery)
    {
        $this->passportDelivery = $passportDelivery;

        return $this;
    }

    /**
     * Get passportDelivery
     *
     * @return \DateTime
     */
    public function getPassportDelivery()
    {
        return $this->passportDelivery;
    }

    /**
     * Set departureDate
     *
     * @param \DateTime $departureDate
     *
     * @return Departure
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /**
     * Get departureDate
     *
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Set returnDate
     *
     * @param \DateTime $returnDate
     *
     * @return Departure
     */
    public function setReturnDate($returnDate)
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    /**
     * Get returnDate
     *
     * @return \DateTime
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * Set passportCollection
     *
     * @param \DateTime $passportCollection
     *
     * @return Departure
     */
    public function setPassportCollection($passportCollection)
    {
        $this->passportCollection = $passportCollection;

        return $this;
    }

    /**
     * Get passportCollection
     *
     * @return \DateTime
     */
    public function getPassportCollection()
    {
        return $this->passportCollection;
    }

    /**
     * Set application
     *
     * @param ExitApplication $application
     *
     * @return Departure
     */
    public function setApplication(ExitApplication $application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return ExitApplication
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set observations
     *
     * @param string $observations
     *
     * @return Departure
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations
     *
     * @return string
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Set results
     *
     * @param string $results
     *
     * @return Departure
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
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $results
     *
     * @return Departure
     */
    public function setResultsFile(File $results = null)
    {
        $this->resultsFile = $results;

        if ($results) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getResultsFile()
    {
        return $this->resultsFile;
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist()
     *
     * @return Departure
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
     * @return Departure
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
     * @return Departure
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
     * Set passport
     *
     * @param Passport $passport
     *
     * @return Departure
     */
    public function setPassport(Passport $passport = null)
    {
        $this->passport = $passport;

        return $this;
    }

    /**
     * Get passport
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
     * @return Departure
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
     * @return Departure
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
}

