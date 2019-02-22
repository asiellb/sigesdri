<?php
/**
 * Created by PhpStorm.
 * User: Jr.AvaLug
 * Date: 04/04/2017
 * Time: 3:51
 */

namespace DRI\ExitBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Economic
{

    private $type;

    private $amount;

    private $source;

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }
}