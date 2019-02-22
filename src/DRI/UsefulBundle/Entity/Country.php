<?php

namespace DRI\UsefulBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DRI\UsefulBundle\Useful\Useful;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="DRI\UsefulBundle\Repository\CountryRepository")
 * @UniqueEntity("spName")
 * @UniqueEntity("iso2")
 * @UniqueEntity("iso3")
 * @UniqueEntity("phoneCode")
 * @Vich\Uploadable
 */
class Country
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
     * @ORM\Column(name="name", type="string", length=50, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=1,max=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="spName", type="string", length=50, unique=true, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(min=1,max=50)
     */
    private $spName;

    /**
     * @var string
     *
     * @ORM\Column(name="continent", type="string", length=30)
     * @Assert\NotBlank()
     * @Assert\Length(min=1,max=30)
     * @Assert\Choice(
     *     choices={
     *          "África",
     *          "América",
     *          "Asia",
     *          "Europa",
     *          "Oceanía",
     *          "Antártida",
     *      },
     *     message="Debe ser una de estas opciones.")
     */
    private $continent;

    /**
     * @var string
     *
     * @ORM\Column(name="area", type="string", length=30, nullable=true)
     * @Assert\Length(min=0,max=30)
     */
    private $area;

    /**
     * @var string
     *
     * @ORM\Column(name="subArea", type="string", length=30, nullable=true)
     * @Assert\Length(min=0,max=30)
     */
    private $subArea;

    /**
     * @var string
     *
     * @ORM\Column(name="iso2", type="string", length=2, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=2,max=2)
     * @Assert\Regex("/^[A-Z]/")
     */
    private $iso2;

    /**
     * @var string
     *
     * @ORM\Column(name="iso3", type="string", length=3, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=3,max=3)
     * @Assert\Regex("/^[A-Z]/")
     */
    private $iso3;

    /**
     * @var string
     *
     * @ORM\Column(name="phoneCode", type="string", length=4, nullable=true, unique=false)
     * @Assert\Length(min=1,max=4)
     * @Assert\Regex("/^[0-9]/")
     */
    private $phoneCode;

    /**
     * @var string
     *
     * @ORM\Column(name="flagImage", type="string", length=255, nullable=true)
     */
    private $flagImage;

    /**
     * @var \File
     *
     * @Vich\UploadableField(mapping="countries_flags_images", fileNameProperty="flagImage")
     */
    private $flagFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastFileUpdate", type="datetime", nullable=true)
     * @Assert\Datetime()
     */
    private $lastFileUpdate;


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getSpName();
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
     * @return Country
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Set spName
     *
     * @param string $spName
     *
     * @return Country
     */
    public function setSpName($spName)
    {
        $this->spName = $spName;

        return $this;
    }

    /**
     * Get spName
     *
     * @return string
     */
    public function getSpName()
    {
        return $this->spName;
    }

    /**
     * Set continent
     *
     * @param string $continent
     *
     * @return Country
     */
    public function setContinent($continent)
    {
        $this->continent = $continent;

        return $this;
    }

    /**
     * Get continent
     *
     * @return string
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * Set area
     *
     * @param string $area
     *
     * @return Country
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set subArea
     *
     * @param string $subArea
     *
     * @return Country
     */
    public function setSubArea($subArea)
    {
        $this->subArea = $subArea;

        return $this;
    }

    /**
     * Get subArea
     *
     * @return string
     */
    public function getSubArea()
    {
        return $this->subArea;
    }

    /**
     * Set iso2
     *
     * @param string $iso2
     *
     * @return Country
     */
    public function setIso2($iso2)
    {
        $this->iso2 = $iso2;

        return $this;
    }

    /**
     * Get iso2
     *
     * @return string
     */
    public function getIso2()
    {
        return $this->iso2;
    }

    /**
     * Set iso3
     *
     * @param string $iso3
     *
     * @return Country
     */
    public function setIso3($iso3)
    {
        $this->iso3 = $iso3;

        return $this;
    }

    /**
     * Get iso3
     *
     * @return string
     */
    public function getIso3()
    {
        return $this->iso3;
    }

    /**
     * Set phoneCode
     *
     * @param string $phoneCode
     *
     * @return Country
     */
    public function setPhoneCode($phoneCode)
    {
        $this->phoneCode = $phoneCode;

        return $this;
    }

    /**
     * Get phoneCode
     *
     * @return string
     */
    public function getPhoneCode()
    {
        return $this->phoneCode;
    }

    /**
     * Set flagImage
     *
     * @param string $flagImage
     *
     * @return Country
     */
    public function setFlagImage($flagImage)
    {
        $this->flagImage = $flagImage;

        return $this;
    }

    /**
     * Get flagImage
     *
     * @return string
     */
    public function getFlagImage()
    {
        return $this->flagImage;
    }

    /**
     * Set flagFile
     *
     * @param File $flagImage
     *
     * @return Country
     */
    public function setFlagFile(File $flagImage = null)
    {
        $this->flagFile = $flagImage;

        if ($flagImage) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->lastFileUpdate = new \DateTime('now');
        }

        return $this;
    }

    /**
     * Get flagFile
     *
     * @return \stdClass
     */
    public function getFlagFile()
    {
        return $this->flagFile;
    }

    /**
     * Set lastFileUpdate
     *
     * @param \DateTime $lastFileUpdate
     *
     * @return Country
     */
    public function setLastFileUpdate($lastFileUpdate)
    {
        $this->lastFileUpdate = $lastFileUpdate;

        return $this;
    }

    /**
     * Get lastFileUpdate
     *
     * @return \DateTime
     */
    public function getLastFileUpdate()
    {
        return $this->lastFileUpdate;
    }
}
