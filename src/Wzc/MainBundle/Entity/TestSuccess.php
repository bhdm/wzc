<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Faq
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TestSuccess extends BaseEntity
{
    /**
     * @ORM\Column(type="integer")
     */
    protected $success;

    /**
     * @ORM\Column(type="integer")
     */
    protected $all;

    /**
     * @param mixed $all
     */
    public function setAll($all)
    {
        $this->all = $all;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->all;
    }

    /**
     * @param mixed $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return mixed
     */
    public function getSuccess()
    {
        return $this->success;
    }



}
