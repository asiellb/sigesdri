<?php

namespace DRI\AgreementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

use DateTime;
use Exception;

use DRI\UsefulBundle\Entity\Country;
use DRI\UserBundle\Entity\User;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Institution
 *
 * @ORM\Table(name="agr_institution")
 * @ORM\Entity(repositoryClass="DRI\AgreementBundle\Repository\InstitutionRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity("name")
 * @UniqueEntity("nameSlug")
 * @UniqueEntity("logo")
 *
 * @Vich\Uploadable
 */
class Institution
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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $nameSlug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=20)
     */
    private $acronym;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="DRI\UsefulBundle\Entity\Country")
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $countryState;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $logo;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="institutions_logos", fileNameProperty="logo")
     */
    private $logoFile;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $rector;

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
     * @ORM\ManyToOne(targetEntity="DRI\UserBundle\Entity\User"), cascade={"persist"})
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
     * Institutional constructor.
     *
     */
    public function __construct()
    {
        $this->createdAt = new DateTime('now');
        $this->updatedAt = new DateTime('now');
    }

    public function __toString()
    {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     *
     * @return Institution
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->nameSlug = Useful::getSlug($this->name);

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nameSlug
     *
     * @param string $nameSlug
     *
     * @return Institution
     */
    public function setNameSlug($nameSlug)
    {
        $this->nameSlug = $nameSlug;

        return $this;
    }

    /**
     * Get nameSlug
     *
     * @return string
     */
    public function getNameSlug()
    {
        return $this->nameSlug;
    }

    /**
     * Set acronym
     *
     * @param string $acronym
     *
     * @return Institution
     */
    public function setAcronym($acronym)
    {
        $this->acronym = $acronym;

        return $this;
    }

    /**
     * Get acronym
     *
     * @return string
     */
    public function getAcronym()
    {
        return $this->acronym;
    }

    /**
     * Set country
     *
     * @param Country $country
     *
     * @return Institution
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
     * Set countryState
     *
     * @param string $countryState
     *
     * @return Institution
     */
    public function setCountryState($countryState)
    {
        $this->countryState = $countryState;

        return $this;
    }

    /**
     * Get countryState
     *
     * @return string
     */
    public function getCountryState()
    {
        return $this->countryState;
    }

    /**
     * Set province
     *
     * @param string $province
     *
     * @return Institution
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province
     *
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Institution
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return Institution
     */
    public function setAddress()
    {
        $this->address = '';

        if ($this->city)
            $this->address .= $this->getCity();

        if($this->province)
            $this->address .= $this->getProvince();

        if ($this->getCountryState())
            $this->address .= $this->getCountryState();

        $this->address .= $this->getCountry();

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Institution
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set logoFile
     *
     * @param File|UploadedFile $logo
     * @return Institution
     * @throws Exception
     */
    public function setLogoFile(File $logo = null)
    {
        $this->logoFile = $logo;

        if ($logo) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTime('now');
        }

        return $this;
    }

    /**
     * Get logo File
     *
     * @return File|null
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Institution
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set rector
     *
     * @param string $rector
     *
     * @return Institution
     */
    public function setRector($rector)
    {
        $this->rector = $rector;

        return $this;
    }

    /**
     * Get rector
     *
     * @return string
     */
    public function getRector()
    {
        return $this->rector;
    }

    /**
     * Set createdAt
     *
     * @ORM\PrePersist
     *
     * @return Institution
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
     * @return Institution
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
     * @return Institution
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

