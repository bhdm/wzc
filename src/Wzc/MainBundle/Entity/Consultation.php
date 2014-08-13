<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * consultation
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Consultation extends BaseEntity
{
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank( message = "поле Вопрос обязательно для заполнения" )
     */
    protected $question;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $answer;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="consultations")
     */
    protected $user;

    public function getId(){
        return $this->id;
    }

    public function __toString(){
        return $this->question;
    }

    /**
     * @param mixed $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
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



}
