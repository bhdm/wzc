<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ForumAnswer
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ForumAnswer extends BaseEntity
{

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $body;

    /**
     * @ORM\ManyToOne(targetEntity="ForumAnswer", inversedBy="by")
     */
    protected $for;

    /**
     * @ORM\OneToMany(targetEntity="ForumAnswer", mappedBy="for")
     */
    protected $by;

//    /**
//     * @ORM\ManyToOne(targetEntity="User", inversedBy="forumAnswers")
//     */
//    protected $author;

    /**
     * @ORM\ManyToOne(targetEntity="ForumQuestion", inversedBy="answers")
     */
    protected $question;

    /**
     * @ORM\ManyToOne(targetEntity="ForumTheme", inversedBy="answers")
     */
    protected $theme;



    /**
     *
     */
    public function __construct()
    {
        $this->by = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->body;
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
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $by
     */
    public function setBy($by)
    {
        $this->by = $by;
    }

    /**
     * @return mixed
     */
    public function getBy()
    {
        return $this->by;
    }

    public function addBy($by){
        $this->by[] = $by;
    }

    public function removeBy($by){
        $this->by->removeElement($by);
    }

    /**
     * @param mixed $for
     */
    public function setFor($for)
    {
        $this->for = $for;
    }

    /**
     * @return mixed
     */
    public function getFor()
    {
        return $this->for;
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
     * @param mixed $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return $this->theme;
    }



}

