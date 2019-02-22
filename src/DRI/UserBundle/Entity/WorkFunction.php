<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 04/04/2017
 * Time: 3:51
 */

namespace DRI\UserBundle\Entity;


class WorkFunction
{
    /**
     * @Assert\NotBlank
     */
    private $function;

    public function setFunction($function)
    {
        $this->function = $function;
    }

    public function getFunction()
    {
        return $this->function;
    }
}