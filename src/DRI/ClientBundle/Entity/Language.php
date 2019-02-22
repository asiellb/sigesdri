<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 13/06/2017
 * Time: 9:57
 */

namespace DRI\ClientBundle\Entity;


class Language
{
    /**
     * @Assert\NotBlank
     */
    private $language;

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }
}