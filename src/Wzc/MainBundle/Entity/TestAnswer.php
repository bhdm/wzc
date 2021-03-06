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
     * @ORM\Column(type="integer")
     */
    protected $priority = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $istrue = 0;

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
     * @param mixed $istrue
     */
    public function setIstrue($istrue = 0)
    {
        $this->istrue = $istrue;
    }

    /**
     * @return mixed
     */
    public function getIstrue()
    {
        return $this->istrue;
    }

    /**
     * @param mixed $priority
     */
    public function setPriority($priority = 0)
    {
        $this->priority = $priority;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }





}
