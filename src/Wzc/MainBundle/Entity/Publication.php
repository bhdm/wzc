<?php

namespace Wzc\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Publication
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @FileStore\Uploadable
 */
class Publication extends BaseEntity
{
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank( message = "поле Заголовок обязательно для заполнения" )
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $keywords;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank( message = "поле Текст обязательно для заполнения" )
     */
    protected $body;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank( message = "поле Анонс обязательно для заполнения" )
     */
    protected $anons;

    /**
     * @Assert\File( maxSize="2M", uploadIniSizeErrorMessage = "Максимальный размер файла - 2 Мб")
     * @FileStore\UploadableField(mapping="publication")
     * @ORM\Column(type="array", nullable=true)
     **/
    protected $image;



    public function getId(){
        return $this->id;
    }

    public function __toString(){
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
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
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
     * @param mixed $anons
     */
    public function setAnons($anons)
    {
        $this->anons = $anons;
    }

    /**
     * @return mixed
     */
    public function getAnons()
    {
        return $this->anons;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }



}
