<?php

namespace DRI\ExitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandFile
 *
 * @ORM\Table(name="ext_command_file")
 * @ORM\Entity(repositoryClass="DRI\ExitBundle\Repository\CommandFileRepository")
 */
class CommandFile
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
     * @ORM\Column(type="text")
     */
    private $ipwActions;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $mwoActions;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $ittActions;


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
     * Set ipwActions
     *
     * @param string $ipwActions
     *
     * @return CommandFile
     */
    public function setIpwActions($ipwActions)
    {
        $this->ipwActions = $ipwActions;

        return $this;
    }

    /**
     * Get ipwActions
     *
     * @return string
     */
    public function getIpwActions()
    {
        return $this->ipwActions;
    }

    /**
     * Set mwoActions
     *
     * @param string $mwoActions
     *
     * @return CommandFile
     */
    public function setMwoActions($mwoActions)
    {
        $this->mwoActions = $mwoActions;

        return $this;
    }

    /**
     * Get mwoActions
     *
     * @return string
     */
    public function getMwoActions()
    {
        return $this->mwoActions;
    }

    /**
     * Set ittActions
     *
     * @param string $ittActions
     *
     * @return CommandFile
     */
    public function setIttActions($ittActions)
    {
        $this->ittActions = $ittActions;

        return $this;
    }

    /**
     * Get ittActions
     *
     * @return string
     */
    public function getIttActions()
    {
        return $this->ittActions;
    }
}

