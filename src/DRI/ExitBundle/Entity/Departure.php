<?php

namespace DRI\ExitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DateTime;
use Exception;

use DRI\ClientBundle\Entity\Client;
use DRI\UserBundle\Entity\User;
use DRI\PassportBundle\Entity\Passport;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Departure
 *
 * @ORM\Table(name="ext_departure")
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
     */
    private $numberSlug;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="DRI\ClientBundle\Entity\Client", inversedBy="departures", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $client;

    /**
     * @ORM\OneToOne(targetEntity="DRI\ExitBundle\Entity\Application", inversedBy="departure")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    private $application;

    /**
     * @var Passport
     *
     * @ORM\ManyToOne(targetEntity="DRI\PassportBundle\Entity\Passport", inversedBy="departures", cascade={"persist"})
     * @ORM\JoinColumn(name="passport_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $passport;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $passportDelivery;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $departureDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $returnDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $passportCollection;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $observations;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $results;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="ext_dep_res_files", fileNameProperty="results")
     */
    private $resultsFile;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $closed;

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
     * Departure constructor.
     *
     */
    public function __construct()
    {
        $this->createdAt = new DateTime('now');
        $this->updatedAt = new DateTime('now');
        $this->closed    = false;
    }

    public function __toString()
    {
        return $this->getNumber();
    }

    public function hasClient(){
        if(is_null($this->client)){
            return null;
        }
        return true;
    }

    public function hasApplication(){
        if(is_null($this->application) || $this->application == false){
            return null;
        }
        return true;
    }

    public function hasPassport(){
        if(is_null($this->passport) || $this->passport == false){
            return null;
        }
        return true;
    }

    public function hasDepartureDate(){
        if(is_null($this->departureDate) || $this->departureDate == false){
            return null;
        }
        return true;
    }

    public function hasPassportDelivery(){
        if(is_null($this->passportDelivery) || $this->passportDelivery == false){
            return null;
        }
        return true;
    }

    public function isClosed(){
        if($this->closed){
            return true;
        }
        return false;
    }

    public function isInProgress(){
        $inDate = $this->getDepartureDate();
        $now    = new DateTime("now");

        if (($now >= $inDate))
            return true;
        return false;
    }

    public function isOnHold(){
        $inDate = $this->getDepartureDate();
        $now    = new DateTime("now");

        if (($now < $inDate))
            return true;
        return false;
    }

    public function isCompleted(){
        $inDate = $this->getReturnDate();
        $now    = new DateTime("now");

        if (($now > $inDate))
            return true;
        return false;
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
     * Set number
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return Departure
     */
    public function setNumber()
    {
        $aDate      = $this->departureDate->format('ymd');
        $aClient    = substr($this->client->getFirstName(), 0, 1).''.substr($this->client->getFirstLastName(), 0, 1).''.$this->client->getId();
        $aPassport  = $this->passport->getId();

        $this->number = $aDate.'-'.$aClient.'-'.$aPassport;

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
     * @return Departure
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
     * Set application
     *
     * @param Application $application
     *
     * @return Departure
     */
    public function setApplication(Application $application)
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
     * Set passportDelivery
     *
     * @param DateTime $passportDelivery
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
     * @return DateTime
     */
    public function getPassportDelivery()
    {
        return $this->passportDelivery;
    }

    /**
     * Set departureDate
     *
     * @param DateTime $departureDate
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
     * @return DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Set returnDate
     *
     * @param DateTime $returnDate
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
     * @return DateTime
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * Set passportCollection
     *
     * @param DateTime $passportCollection
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
     * @return DateTime
     */
    public function getPassportCollection()
    {
        return $this->passportCollection;
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
     * @param File|UploadedFile $results
     * @return Departure
     * @throws Exception
     */
    public function setResultsFile(File $results = null)
    {
        $this->resultsFile = $results;

        if ($results) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTime('now');
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
     * Set closed
     *
     * @param boolean $closed
     *
     * @return Departure
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
     * Set createdAt
     *
     * @ORM\PrePersist()
     *
     * @return Departure
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
     * @return Departure
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
