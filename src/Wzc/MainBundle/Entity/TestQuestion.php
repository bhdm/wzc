<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TestQuestion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TestQuestion extends BaseEntity
{
    /**
     * @ORM\OneToMany(targetEntity="TestAnswer", mappedBy="answers")
     */
    protected $answers;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message = "поле Вопрос обязательно для заполнения" )
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $body;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isText = 0;


    public function getId(){
        return $this->id;
    }

    public function __toString(){
        return $this->title;
    }

    public function __construct(){
        $this->answers = new ArrayCollection();
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
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
     * @param mixed $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    public function addAnswer($answer){
        $this->answers[] = $answer;
    }

    public function removeAnswer($answer){
        $this->answers->removeElement($answer);
    }

    /**
     * @param mixed $isText
     */
    public function setIsText($isText)
    {
        $this->isText = $isText;
    }

    /**
     * @return mixed
     */
    public function getIsText()
    {
        return $this->isText;
    }


}
