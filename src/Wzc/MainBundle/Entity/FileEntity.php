<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Faq
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class File extends BaseEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="files")
     */
    protected $user;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;


    protected $file;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="childs")
     */
    protected $parent;

    /**
     * @ORM\ManyToOne(targetEntity="File", inversedBy="parent")
     */
    protected $childs;

    /**
     * file|folder|image
     * @ORM\Column(type="string", length=6)
     */
    protected $type;

    public function __toString(){
        return $this->title;
    }

    public function __construct(){
        $this->childs = new ArrayCollection();
    }

    /**
     * @param mixed $childs
     */
    public function setChilds($childs)
    {
        $this->childs = $childs;
    }

    /**
     * @return mixed
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
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
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
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