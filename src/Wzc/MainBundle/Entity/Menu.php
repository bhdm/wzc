<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Menu
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Menu extends BaseEntity
{
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message = "поле Заголовок обязательно для заполнения" )
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent")
     */
    protected $childs;

    /**
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="childs")
     */
    protected $parent;

    /**
     * @ORM\Column(type="string")
     */
    protected $url;

    public function __construct(){
        $this->childs = new ArrayCollection();
    }

    public function __toString(){
        return $this->title;
    }

    public function getId(){
        return $this->id;
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
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
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

    public function addChild($menu){
        $this->childs[] = $menu;
    }

    public function removeChild($menu){
        $this->childs->removeElement($menu);
    }

}
