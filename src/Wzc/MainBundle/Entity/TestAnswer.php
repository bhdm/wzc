<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TestAnswer
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TestAnswer extends BaseEntity
{
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message = "поле Ответ обязательно для заполнения" )
     */
    protected $title;

    /**
     * @ORM\ManyToOne(targetEntity="TestQuestion", inversedBy="answers")
     */
    protected $question;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $true = 0;

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
     * @param mixed $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $true
     */
    public function setTrue($true = 0)
    {
        $this->true = $true;
    }

    /**
     * @return mixed
     */
    public function getTrue()
    {
        return $this->true;
    }



}
