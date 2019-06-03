<?php

namespace DRI\AgreementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DRI\ClientBundle\Entity\Language;
use DRI\ClientBundle\Entity\Organization;
use DRI\PassportBundle\Entity\Passport;
use DRI\PassportBundle\Entity\Application as PassportApplication;
use DRI\ExitBundle\Entity\Departure;
use DRI\AgreementBundle\Entity\Application;
use DRI\UsefulBundle\Entity\Country;
use DRI\UsefulBundle\Entity\Area;
use DRI\UserBundle\Entity\User;
use DRI\UsefulBundle\Useful\Useful;



/**
 * Institutional
 *
 * @ORM\Table(name="agr_institutional")
 * @ORM\Entity(repositoryClass="DRI\AgreementBundle\Repository\InstitutionalRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity("number")
 * @UniqueEntity("numberSlug")
 *
 * @Vich\Uploadable
 */
class Institutional
{

    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    INSTITUTIONAL'S CONSTANTS
     * *
     * ********************************************************************************
     **********************************************************************************/

    const INSTITUTIONAL_ACTION_TYPE = [
        'FIR' => 'Firma',
        'OFI' => 'Reactivación',
    ];

    const INSTITUTIONAL_ACTION_TYPE_CHOICE = [
        'Firma'         =>'FIR',
        'Reactivación'  =>'REA',
    ];



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    INSTITUTIONAL'S VARIABLES
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
     * @ORM\Column(type="string")
     *
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $numberSlug;

    /**
     * @ORM\OneToOne(targetEntity="DRI\AgreementBundle\Entity\Application", inversedBy="institutional")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    private $application;

    /**
     * @var Institution
     *
     * @ORM\ManyToOne(targetEntity="DRI\AgreementBundle\Entity\Institution")
     */
    private $institution;

    /**
     * @var Institution
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Country", inversedBy="institutionals")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $actionType;

    /**
     * One Student has One Student.
     * @ORM\OneToOne(targetEntity="DRI\AgreementBundle\Entity\Institutional")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $mesApproval;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $digitalCopy;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="agr_int_dco_files", fileNameProperty="digitalCopy")
     */
    private $digitalCopyFile;

    /**
     * @var string
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Date()
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Date()
     */
    private $updatedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DRI\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="created_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $createdBy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DRI\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="updated_user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $lastUpdateBy;

    /**
     * Many ManagerTravelPlan have Many Contries.
     * @ORM\ManyToMany(targetEntity="DRI\UsefulBundle\Entity\Area")
     * @ORM\JoinTable(name="agr_institutionals_areas",
     *      joinColumns={@ORM\JoinColumn(name="institutional_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="area_id", referencedColumnName="id")}
     *      )
     */
    private $benefitedAreas;



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    INSTITUTIONAL'S CONSTRUCTOR & TO_STRING METHOD
     * *
     * ********************************************************************************
     **********************************************************************************/


    /**
     * Institutional constructor.
     *
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->benefitedAreas = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNumber();
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    INSTITUTIONAL'S ENTITY METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/




    public function isActive(){
        $inDate = $this->getStartDate();
        $exDate = $this->getEndDate();
        $now    = new \DateTime("now");

        if (($now >= $inDate) && ($now <= $exDate))
            return true;
        return false;
    }

    public function isExpired(){
        $exDate = $this->getEndDate();
        $now    = new \DateTime("now");

        if($exDate != null){
            if ($now > $exDate)
                return true;
        }
        return false;
    }

    public function isForExpiring(){
        $exDate = $this->getEndDate();
        $now    = new \DateTime("now");
        if($exDate){
            $toExDate = clone $exDate;
            $toExDate->sub(new \DateInterval('P6M'));

            if (($now >= $toExDate) && ($now <= $exDate))
                return true;
        }

        return false;
    }

    public function getState(){
        if($this->isExpired()){
            $this->state = 'VEN';
        }elseif ($this->isForExpiring()){
            $this->state = 'PVEN';
        }elseif ($this->isActive()){
            $this->state = 'ACT';
        }

        return $this->state;
    }

    public function hasApplication(){
        if(is_null($this->application) || $this->application == false){
            return null;
        }
        return true;
    }

    public function hasInstitution(){
        if(is_null($this->institution) || $this->institution == false){
            return null;
        }
        return true;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    INSTITUTIONAL'S GET & SET METHODS
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
     * @param string $number
     *
     * @return Institutional
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
     * @return Institutional
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
     * Set institution
     *
     * @param string $institution
     *
     * @return Institutional
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
     * Set actionType
     *
     * @param string $actionType
     *
     * @return Institutional
     */
    public function setActionType($actionType)
    {
        $this->actionType = $actionType;

        return $this;
    }

    /**
     * Get actionType
     *
     * @return string
     */
    public function getActionType()
    {
        return $this->actionType;
    }

    /**
     * Set parent
     *
     * @param Institutional $parent
     *
     * @return Institutional
     */
    public function setParent(Institutional $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Institutional
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Institutional
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Institutional
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set mesApproval
     *
     * @param string $mesApproval
     *
     * @return Institutional
     */
    public function setMesApproval($mesApproval)
    {
        $this->mesApproval = $mesApproval;

        return $this;
    }

    /**
     * Get mesApproval
     *
     * @return string
     */
    public function getMesApproval()
    {
        return $this->mesApproval;
    }

    /**
     * Set digitalCopy
     *
     * @param string $digitalCopy
     *
     * @return Institutional
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
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $digitalCopy
     *
     * @return Institutional
     */
    public function setDigitalCopyFile(File $digitalCopy = null)
    {
        $this->digitalCopyFile = $digitalCopy;

        if ($digitalCopy) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
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
     * Set application
     *
     * @param Application $application
     *
     * @return Institutional
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
     * Set createdAt
     *
     * @ORM\PrePersist
     *
     * @return Institutional
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
     * @return Institutional
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
     * Set createdBy.
     *
     * @param User $createdBy
     *
     *
     * @return $this
     */
    public function setCreatedBy(User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
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
     * @return Institutional
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
     * Add benefitedArea
     *
     * @param Area $benefitedArea
     *
     * @return Institutional
     */
    public function addBenefitedArea(Area $benefitedArea)
    {
        $this->benefitedAreas[] = $benefitedArea;

        return $this;
    }

    /**
     * Remove benefitedArea
     *
     * @param Area $benefitedArea
     */
    public function removeBenefitedArea(Area $benefitedArea)
    {
        $this->benefitedAreas->removeElement($benefitedArea);
    }

    /**
     * Get benefitedAreas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBenefitedAreas()
    {
        return $this->benefitedAreas;
    }


    /**
     * Set country
     *
     * @param Country $country
     *
     * @return Institutional
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Institution
     */
    public function getCountry()
    {
        return $this->country;
    }



    /**********************************************************************************
     * ********************************************************************************
     * *
     * *    INSTITUTIONAL'S ADITIONALS METHODS
     * *
     * ********************************************************************************
     **********************************************************************************/




    static function actionType_AcronimToName($actionType){
        switch ($actionType){
            case 'FIR': return 'Firma';break;
            case 'REA': return 'Reactivación';break;
            default: return 'Tipo de Acción No Definido';break;
        }
    }

    static function actionType_NameToAcronim($actionType){
        switch ($actionType){
            case 'Firma': return 'COR';break;
            case 'Reactivación': return 'DIP';break;
            default: return 'Tipo de Acción No Definido';break;
        }
    }
}
