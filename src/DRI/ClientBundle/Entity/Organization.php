<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 13/06/2017
 * Time: 9:59
 */

namespace DRI\ClientBundle\Entity;


class Organization
{
    /**
     * @Assert\NotBlank
     */
    private $organization;

    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    public function getOrganization()
    {
        return $this->organization;
    }
}