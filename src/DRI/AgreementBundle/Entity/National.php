<?php

namespace DRI\AgreementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DateTime;
use Exception;

use DRI\UsefulBundle\Entity\Country;
use DRI\UserBundle\Entity\User;

/**
 * National
 *
 * @ORM\Table(name="agr_national")
 * @ORM\Entity(repositoryClass="DRI\AgreementBundle\Repository\NationalRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Vich\Uploadable
 */
class National
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
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Country")
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $benefitedAreas;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $state;


    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\Date()
     */
    private $createdAt;

    /**
     * @var DateTime
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
     * National constructor.
     *
     */
    public function __construct()
    {
        $this->createdAt = new DateTime('now');
        $this->updatedAt = new DateTime('now');
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
     * Set benefitedAreas
     *
     * @param string $benefitedAreas
     *
     * @return National
     */
    public function setBenefitedAreas($benefitedAreas)
    {
        $this->benefitedAreas = $benefitedAreas;

        return $this;
    }

    /**
     * Get benefitedAreas
     *
     * @return string
     */
    public function getBenefitedAreas()
    {
        return $this->benefitedAreas;
    }

    /**
     * Set state
     *
     * @param boolean $state
     *
     * @return National
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param Country $country
     *
     * @return National
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
     * Set createdAt
     *
     * @ORM\PrePersist
     *
     * @return National
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
     * @ORM\PreUpdate
     *
     * @return National
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
     * @return National
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
