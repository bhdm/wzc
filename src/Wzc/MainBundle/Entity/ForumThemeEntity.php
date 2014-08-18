<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ForumTheme
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ForumTheme extends BaseEntity
{
    /**
     * @Assert\NotBlank( message = "Заголовок темы обязателен для заполнения" )
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $body;

    /**
     * @ORM\OneToMany(targetEntity="ForumQuestion", mappedBy="theme")
     */
    protected $questions;

    /**
     * @ORM\OneToMany(targetEntity="ForumAnswer", mappedBy="theme")
     */
    protected $answers;

    /**
     *
     */
    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->title;
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
     * @param mixed $questions
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    public function addQuestion($question){
        $this->questions[] = $question;
    }

    public function removeQuestion($question){
        $this->questions->removeElement($question);
    }

}

