<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 30/10/2016
 * Time: 4:06
 */

namespace DRI\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

use DateTime;
use Exception;

/**
 * @ORM\Entity
 * @ORM\Table(name="usr_user")
 *
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\NotBlank(message="Por favor entre su nombre.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=100,
     *     minMessage="El nombre es muy corto.",
     *     maxMessage="El nombre es muy largo.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Assert\NotBlank(
     *     message="Por favor entre sus apellidos.",
     *     groups={"Registration", "Profile"}
     * )
     * @Assert\Length(
     *     min=3,
     *     max=100,
     *     minMessage="El apellido es muy corto.",
     *     maxMessage="El apellido es muy largo.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     *
     * @Assert\Choice(
     *     choices={
     *          "Tec",
     *          "Lic",
     *          "Ing",
     *          "MSc",
     *          "DrC",
     *          "DraC"
     *      },
     *     message="Debe ser una de estas opciones: {Tec, Lic, Ing, MSc, DrC, DraC}")
     */
    private $scienceCategory;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Assert\Length(
     *     max=50,
     *     maxMessage="El cargo es muy largo.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $position;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $employee = false;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @Assert\Length(
     *     max=50,
     *     maxMessage="El departamento de trabajo es muy largo.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $workDepartment;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     *
     * @Assert\Length(
     *     max=8,
     *     maxMessage="El número de teléfono es muy largo.",
     *     groups={"Registration", "Profile"}
     * )
     * @Assert\Regex("/^[0-9]/")
     */
    private $workPhone;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     *
     * @Assert\Length(
     *     max=8,
     *     maxMessage="El número de teléfono es muy largo.",
     *     groups={"Registration", "Profile"}
     * )
     * @Assert\Regex("/^[0-9]/")
     */
    private $homePhone;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     *
     * @Assert\Length(
     *     max=8,
     *     maxMessage="El número de teléfono es muy largo.",
     *     groups={"Registration", "Profile"}
     * )
     * @Assert\Regex("/^[0-9]/")
     */
    private $celPhone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userImage;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="users_images", fileNameProperty="userImage")
     */
    private $userImageFile;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $lastUpdateImage;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $about;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $home;






    public function __construct()
    {
        parent::__construct();

        $this->lastUpdateImage = new DateTime('now');
        $this->firstName = '';
        $this->lastName = '';
        $this->userImage = '';
    }


    public function __toString()
    {
        return $this->getFullName();
    }


    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set fullName
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return User
     */
    public function setFullName()
    {
        $this->fullName = $this->firstName.' '.$this->lastName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set userImage
     *
     * @param string $userImage
     *
     * @return User
     */
    public function setUserImage($userImage)
    {
        $this->userImage = $userImage;

        return $this;
    }

    /**
     * Get userImage
     *
     * @return string
     */
    public function getUserImage()
    {
        return $this->userImage;
    }

    /**
     * @param File|UploadedFile $userImage
     *
     * @return User
     * @throws Exception
     */
    public function setUserImageFile(File $userImage = null)
    {
        $this->userImageFile = $userImage;

        if ($userImage) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->lastUpdateImage = new DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getUserImageFile()
    {
        return $this->userImageFile;
    }

    /**
     * Set lastUpdateImage
     *
     * @param DateTime $lastUpdateImage
     *
     * @return User
     */
    public function setLastUpdateImage($lastUpdateImage)
    {
        $this->lastUpdateImage = $lastUpdateImage;

        return $this;
    }

    /**
     * Get lastUpdateImage
     *
     * @return DateTime
     */
    public function getLastUpdateImage()
    {
        return $this->lastUpdateImage;
    }

    /**
     * Set scienceCategory
     *
     * @param string $scienceCategory
     *
     * @return User
     */
    public function setScienceCategory($scienceCategory)
    {
        $this->scienceCategory = $scienceCategory;

        return $this;
    }

    /**
     * Get scienceCategory
     *
     * @return string
     */
    public function getScienceCategory()
    {
        return $this->scienceCategory;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return User
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set workDepartment
     *
     * @param string $workDepartment
     *
     * @return User
     */
    public function setWorkDepartment($workDepartment)
    {
        $this->workDepartment = $workDepartment;

        return $this;
    }

    /**
     * Get workDepartment
     *
     * @return string
     */
    public function getWorkDepartment()
    {
        return $this->workDepartment;
    }

    /**
     * Set workPhone
     *
     * @param string $workPhone
     *
     * @return User
     */
    public function setWorkPhone($workPhone)
    {
        $this->workPhone = $workPhone;

        return $this;
    }

    /**
     * Get workPhone
     *
     * @return string
     */
    public function getWorkPhone()
    {
        return $this->workPhone;
    }

    /**
     * Set homePhone
     *
     * @param string $homePhone
     *
     * @return User
     */
    public function setHomePhone($homePhone)
    {
        $this->homePhone = $homePhone;

        return $this;
    }

    /**
     * Get homePhone
     *
     * @return string
     */
    public function getHomePhone()
    {
        return $this->homePhone;
    }

    /**
     * Set celPhone
     *
     * @param string $celPhone
     *
     * @return User
     */
    public function setCelPhone($celPhone)
    {
        $this->celPhone = $celPhone;

        return $this;
    }

    /**
     * Get celPhone
     *
     * @return string
     */
    public function getCelPhone()
    {
        return $this->celPhone;
    }

    /**
     * Set employee
     *
     * @param boolean $employee
     *
     * @return User
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return boolean
     */
    public function getEmployee()
    {
        return $this->employee;
    }


    /**
     * Set about
     *
     * @param string $about
     *
     * @return User
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set home
     *
     * @param string $home
     *
     * @return User
     */
    public function setHome($home)
    {
        $this->home = $home;

        return $this;
    }

    /**
     * Get home
     *
     * @return string
     */
    public function getHome()
    {
        return $this->home;
    }
}
