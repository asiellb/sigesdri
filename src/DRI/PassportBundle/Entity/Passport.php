<?php

namespace DRI\PassportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DateTime;
use DateInterval;
use Exception;

use DRI\ExitBundle\Entity\Departure;
use DRI\ClientBundle\Entity\Client;
use DRI\UserBundle\Entity\User;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Passport
 *
 * @ORM\Table(name="pas_passport")
 * @ORM\Entity(repositoryClass="DRI\PassportBundle\Repository\PassportRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("number")
 *
 * @Vich\Uploadable
 */
class Passport
{

    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S CONSTANTS
     * *
     * ********************************************************************************
     **********************************************************************************/

    public static $PASSPORT_TYPE = [
        'COR' => 'Corriente',
        //'DIP' => 'Diplomático',
        'OFI' => 'Oficial',
        //'SER' => 'Servicio',
        //'MAR' => 'Marino',
    ];

    public static $PASSPORT_TYPE_CHOICE = [
        'Corriente'   =>'COR',
        //'Diplomático' =>'DIP',
        'Oficial'     =>'OFI',
        //'Servicio'    =>'SER',
        //'Marino'      =>'MAR',
    ];

    public static $PASSPORT_STATE = [
        'ACT'  => 'Activo',
        'PPRO' => 'Por Prorrogar',
        'RPRO' => 'Requiere Prorrogar',
        'PVEN' => 'Por Vencer',
        'VEN'  => 'Vencido',
        'BAJ'  => 'Baja',
    ];

    public static $PASSPORT_STATE_CHOICE = [
        'Activo'             =>'ACT',
        'Por Prorrogar'      =>'PPRO',
        'Requiere Prorrogar' =>'RPRO',
        'Por Vencer'         =>'PVEN',
        'Vencido'            =>'VEN',
        'Baja'               =>'BAJ',
    ];



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S VARIABLES
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
     * @ORM\OneToOne(targetEntity="DRI\PassportBundle\Entity\Application", inversedBy="passport")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    private $application;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=7, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=7, max=7)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=7, unique=true)
     */
    private $numberSlug;

    /**
     * @var Client
     *
     * @ORM\ManyToOne(targetEntity="DRI\ClientBundle\Entity\Client", inversedBy="passports", cascade={"persist"})
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $holder;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $issueDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $expiryDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=3,)
     * @Assert\Choice(choices={"COR", "DIP", "OFI", "SER", "MAR"})
     */
    private $type;

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
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     */
    private $firstExtension;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $firstExtensionDate;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     */
    private $secondExtension;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $secondExtensionDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="drop_passport", type="boolean", nullable=true)
     *
     */
    private $drop;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     *
     * @Assert\Date()
     */
    private $dropDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dropReason;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=11)
     */
    private $clientCi;

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
    private $inStore;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Date()
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DRI\ExitBundle\Entity\Departure", mappedBy="passport")
     */
    private $departures;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DRI\PassportBundle\Entity\Control", mappedBy="passport")
     */
    private $controls;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S CONSTRUCTOR & TO_STRING METHOD
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * Passport constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->controls     = new ArrayCollection();
        $this->firstPage    = '';
        $this->dropDate     = null;
        $this->createdAt    = new DateTime('now');
        $this->updatedAt    = new DateTime('now');
        $this->inStore      = true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getNumber();
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S ENTITY METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * @return bool|null
     */
    public function hasHolder(){
        if(is_null($this->holder)){
            return null;
        }
        return true;
    }

    /**
     * @return bool|null
     */
    public function hasApplication(){
        if(is_null($this->application) || $this->application == false){
            return null;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isClosed(){
        if($this->closed){
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isInStore(){
        if($this->inStore){
            return true;
        }
        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isActive(){
        $inDate = $this->getIssueDate();
        $exDate = $this->getExpiryDate();
        $now    = new DateTime("now");

        if (($now >= $inDate) && ($now <= $exDate))
            return true;
        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isExpired(){
        $exDate = $this->getExpiryDate();
        $now    = new DateTime("now");

        if ($now > $exDate)
            return true;
        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isForExtend(){
        if($this->getType() == 'COR'){
            $issueDate      = $this->getIssueDate();
            $firstLimitExt  = clone $issueDate;
            $firstLimitExt->add(new DateInterval('P2Y'));
            $secondLimitExt = clone $issueDate;
            $secondLimitExt->add(new DateInterval('P4Y'));
            $now            = new DateTime("now");

            if(!$this->getFirstExtension()){
                $firstAdvise = clone $firstLimitExt;
                $firstAdvise->sub(new DateInterval('P3M'));

                if ($now >= $firstAdvise)
                    return true;
            }elseif (!$this->getSecondExtension()){
                $secondAdvise = clone $secondLimitExt;
                $secondAdvise->sub(new DateInterval('P3M'));

                if ($now >= $secondAdvise)
                    return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isRequireExtend(){
        if($this->getType() == 'COR'){
            $issueDate      = $this->getIssueDate();
            $firstLimitExt  = clone $issueDate;
            $firstLimitExt->add(new DateInterval('P2Y'));
            $secondLimitExt = clone $issueDate;
            $secondLimitExt->add(new DateInterval('P4Y'));
            $now            = new DateTime("now");

            if(!$this->getFirstExtension()){
                if ($now >= $firstLimitExt)
                    return true;
            }elseif (!$this->getSecondExtension()){
                if ($now >= $secondLimitExt)
                    return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isForExpiring(){
        $exDate = $this->getExpiryDate();
        $toExDate = clone $exDate;
        $toExDate->sub(new DateInterval('P6M'));
        $now    = new DateTime("now");

        if (($now >= $toExDate) && ($now <= $exDate))
            return true;
        return false;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getState(){
        $state = '';
        if($this->isExpired()){
            $state = 'VEN';
        }elseif ($this->isForExpiring()){
            $state = 'PVEN';
        }elseif ($this->getDrop() == true){
            $state = 'BAJ';
        }elseif ($this->isRequireExtend()){
            $state = 'RPRO';
        }elseif ($this->isForExtend()){
            $state = 'PPRO';
        }elseif ($this->isActive()){
            $state = 'ACT';
        }

        return $state;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S GET & SET METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/




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
     * @param File|UploadedFile $firstPage
     *
     * @return Passport
     * @throws Exception
     */
    public function setFirstPageFile(File $firstPage = null)
    {
        $this->firstPageFile = $firstPage;

        if ($firstPage) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTime('now');
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
     * @return Passport
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
     * Set issueDate
     *
     * @param DateTime $issueDate
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
     * @return DateTime
     */
    public function getIssueDate()
    {
        return $this->issueDate;
    }

    /**
     * Set expiryDate
     *
     * @param DateTime $expiryDate
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
     * @return DateTime
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
     * Set drop
     *
     * @param boolean $drop
     *
     * @return Passport
     * @throws Exception
     */
    public function setDrop($drop)
    {
        $this->drop = $drop;

        if ($drop){
            $this->dropDate = new DateTime('now');
            $this->closed = true;
            $this->inStore = false;
        }

        return $this;
    }

    /**
     * Get drop
     *
     * @return boolean
     */
    public function getDrop()
    {
        return $this->drop;
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
     * @return DateTime
     */
    public function getDropDate()
    {
        return $this->dropDate;
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist
     *
     * @return Passport
     * @throws Exception
     */
    public function setCreatedAt()
    {
        $this->createdAt = new DateTime('now');;

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
     * @ORM\PreUpdate
     *
     * @return Passport
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
     * @return Client
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
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set dropDate
     *
     * @param DateTime $dropDate
     *
     * @return Passport
     */
    public function setDropDate($dropDate)
    {
        $this->dropDate = $dropDate;

        return $this;
    }

    /**
     * Set application
     *
     * @param Application $application
     *
     * @return Passport
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
     * @param Departure $departure
     *
     * @return Passport
     */
    public function addDeparture(Departure $departure)
    {
        $this->departures[] = $departure;

        return $this;
    }

    /**
     * Remove departure
     *
     * @param Departure $departure
     */
    public function removeDeparture(Departure $departure)
    {
        $this->departures->removeElement($departure);
    }

    /**
     * Get departures
     *
     * @return Collection
     */
    public function getDepartures()
    {
        return $this->departures;
    }

    /**
     * Set closed
     *
     * @param boolean $closed
     *
     * @return Passport
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
     * Set inStore
     *
     * @param boolean $inStore
     *
     * @return Passport
     */
    public function setInStore($inStore)
    {
        $this->inStore = $inStore;

        return $this;
    }

    /**
     * Get inStore
     *
     * @return boolean
     */
    public function getInStore()
    {
        return $this->inStore;
    }


    /**
     * Add control
     *
     * @param Control $control
     *
     * @return Passport
     */
    public function addControl(Control $control)
    {
        $this->controls[] = $control;

        return $this;
    }

    /**
     * Remove control
     *
     * @param Control $control
     */
    public function removeControl(Control $control)
    {
        $this->controls->removeElement($control);
    }

    /**
     * Get controls
     *
     * @return Collection
     */
    public function getControls()
    {
        return $this->controls;
    }


    /**
     * Set firstExtension
     *
     * @param boolean $firstExtension
     *
     * @return Passport
     */
    public function setFirstExtension($firstExtension)
    {
        $this->firstExtension = $firstExtension;

        return $this;
    }

    /**
     * Get firstExtension
     *
     * @return boolean
     */
    public function getFirstExtension()
    {
        return $this->firstExtension;
    }

    /**
     * Set firstExtensionDate
     *
     * @param DateTime $firstExtensionDate
     *
     * @return Passport
     */
    public function setFirstExtensionDate($firstExtensionDate)
    {
        $this->firstExtensionDate = $firstExtensionDate;

        return $this;
    }

    /**
     * Get firstExtensionDate
     *
     * @return DateTime
     */
    public function getFirstExtensionDate()
    {
        return $this->firstExtensionDate;
    }

    /**
     * Set secondExtension
     *
     * @param boolean $secondExtension
     *
     * @return Passport
     */
    public function setSecondExtension($secondExtension)
    {
        $this->secondExtension = $secondExtension;

        return $this;
    }

    /**
     * Get secondExtension
     *
     * @return boolean
     */
    public function getSecondExtension()
    {
        return $this->secondExtension;
    }

    /**
     * Set secondExtensionDate
     *
     * @param DateTime $secondExtensionDate
     *
     * @return Passport
     */
    public function setSecondExtensionDate($secondExtensionDate)
    {
        $this->secondExtensionDate = $secondExtensionDate;

        return $this;
    }

    /**
     * Get secondExtensionDate
     *
     * @return DateTime
     */
    public function getSecondExtensionDate()
    {
        return $this->secondExtensionDate;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    PASSPORT'S ADITIONALS METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * @param $type
     * @return string
     */
    static function type_AcronimToName($type){
        switch ($type){
            case 'COR': return 'Corriente';break;
            case 'DIP': return 'Diplomático';break;
            case 'OFI': return 'Oficial';break;
            case 'SER': return 'Servicio';break;
            case 'MAR': return 'Marino';break;
            default: return 'Tipo de Pasaporte No Definido';break;
        }
    }

    /**
     * @param $type
     * @return string
     */
    static function type_NameToAcronim($type){
        switch ($type){
            case 'Corriente': return 'COR';break;
            case 'Diplomático': return 'DIP';break;
            case 'Oficial': return 'OFI';break;
            case 'Servicio': return 'SER';break;
            case 'Marino': return 'MAR';break;
            default: return 'Tipo de Pasaporte No Definido';break;
        }
    }

    /**
     * @param $state
     * @return string
     */
    static function state_AcronimToName($state){
        switch ($state){
            case 'ACT': return 'Activo';break;
            case 'PPRO': return 'Por Prorrogar';break;
            case 'RPRO': return 'Requiere Prorrogar';break;
            case 'PVEN': return 'Por Vencer';break;
            case 'VEN': return 'Vencido';break;
            case 'BAJ': return 'Baja';break;
            default: return 'Estado de Pasaporte No Definido';break;
        }
    }

    /**
     * @param $state
     * @return string
     */
    static function state_NameToAcronim($state){
        switch ($state){
            case 'Activo': return 'ACT';break;
            case 'Por Prorrogar': return 'PPRO';break;
            case 'Requiere Prorrogar': return 'RPRO';break;
            case 'Por Vencer': return 'PVEN';break;
            case 'Vencido': return 'VEN';break;
            case 'Baja': return 'BAJ';break;
            default: return 'Estado de Pasaporte No Definido';break;
        }
    }

}
