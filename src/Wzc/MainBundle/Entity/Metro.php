<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Map
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Metro extends BaseEntity
{
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message = "поле Заголовок обязательно для заполнения" )
     */
    protected $title;

    /**
     * Долгота
     * @ORM\Column(type="string", nullable=true)
     */
    protected $longitude;

    /**
     * Широта
     * @ORM\Column(type="string", nullable=true)
     */
    protected $latitude;



    public function getId(){
        return $this->id;
    }

    public function __toString(){
        return $this->title;
    }


    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

}
