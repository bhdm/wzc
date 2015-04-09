<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Статистика посещений и скачиваний
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="StatsRepository")
 */
class Stats extends BaseEntity
{
    /**
     * Может быть test, doc и map
     * @ORM\Column(name="category", type="string", nullable=true)
     */
    protected $category;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ip;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->category;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->category = $type;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }



    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }



}