<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TestStatistic
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TestStatistic extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="testStatistic")
     */
    protected $user;

    /**
     * @ORM\Column(type="integer")
     */
    protected $balls;

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $balls
     */
    public function setBalls($balls)
    {
        $this->balls = $balls;
    }

    /**
     * @return mixed
     */
    public function getBalls()
    {
        return $this->balls;
    }


}