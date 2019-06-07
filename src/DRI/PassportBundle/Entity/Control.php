<?php

namespace DRI\PassportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DateTime;
use Exception;

use DRI\UserBundle\Entity\User;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Control
 *
 * @ORM\Table(name="pas_control")
 * @ORM\Entity(repositoryClass="DRI\PassportBundle\Repository\ControlRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity("number")
 * @UniqueEntity("numberSlug")
 *
 * @Vich\Uploadable
 */
class Control
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
     *
     */
    private $numberSlug;

    /**
     * @var Passport
     *
     * @ORM\ManyToOne(targetEntity="DRI\PassportBundle\Entity\Passport", inversedBy="controls", cascade={"persist"})
     * @ORM\JoinColumn(name="passport_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $passport;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $deliveryDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DRI\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="receives_client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $receivesSpecialist;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $pickUpDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="DRI\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="delivers_client_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $deliversSpecialist;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $closed;

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
     * Passport Control constructor.
     *
     */
    public function __construct()
    {
        $this->closed    = false;
        $this->createdAt = new DateTime('now');
        $this->updatedAt = new DateTime('now');
    }

    public function __toString()
    {
        $controlNumber = $this->getNumber();

        return $controlNumber;
    }

    public function hasPassport(){
        if(is_null($this->passport)){
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
     *
     * @return Control
     */
    public function setNumber()
    {
        $passport   = $this->getPassport();
        $date       = date_format($this->getDeliveryDate(), "ymd");

        $this->number = $passport.$date;
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
     * @return Control
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
     * Set passport
     *
     * @param Passport $passport
     *
     * @return Control
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
     * Set deliveryDate
     *
     * @param DateTime $deliveryDate
     *
     * @return Control
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * Get deliveryDate
     *
     * @return DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * Set receivesSpecialist
     *
     * @param User $receivesSpecialist
     *
     * @return Control
     */
    public function setReceivesSpecialist(User $receivesSpecialist = null)
    {
        $this->receivesSpecialist = $receivesSpecialist;

        return $this;
    }

    /**
     * Get receivesSpecialist
     *
     * @return User
     */
    public function getReceivesSpecialist()
    {
        return $this->receivesSpecialist;
    }

    /**
     * Set pickUpDate
     *
     * @param DateTime $pickUpDate
     *
     * @return Control
     */
    public function setPickUpDate($pickUpDate)
    {
        $this->pickUpDate = $pickUpDate;

        if(is_null($this->pickUpDate)){
            $this->closed = false;
        }else{
            $this->closed = true;
        }

        return $this;
    }

    /**
     * Get pickUpDate
     *
     * @return DateTime
     */
    public function getPickUpDate()
    {
        return $this->pickUpDate;
    }

    /**
     * Set deliversSpecialist
     *
     * @param User $deliversSpecialist
     *
     * @return Control
     */
    public function setDeliversSpecialist(User $deliversSpecialist = null)
    {
        $this->deliversSpecialist = $deliversSpecialist;

        return $this;
    }

    /**
     * Get deliversSpecialist
     *
     * @return User
     */
    public function getDeliversSpecialist()
    {
        return $this->deliversSpecialist;
    }

    /**
     * Set close
     *
     * @param boolean $closed
     *
     * @return Control
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
     * @ORM\PrePersist
     *
     * @return Control
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
     * @return Control
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
     * @return Control
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
     * @return Control
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

